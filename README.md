这是一个简单的laravel商品项目
以下记录项目创建流程
1.创建项目
composer create-project laravel/laravel product-laravel9 --prefer-dist "9.1.*"
2.




## 作业：练习写商品的增删改查

- 要求使用 laravel 9.x 使用下面的数据库表结构写出商品表的增删改查的代码，要在页面上可以操作商品的增删改查
- 需要考虑到商品的图片，商品的分类，商品的品牌，商品的库存
- 商品列表页面还需要可以根据商品的分类，品牌，价格，名称等条件进行筛选
- 还需要分页功能

---

1. 商品表（products）的创建SQL：
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '商品ID',
    name VARCHAR(255) NOT NULL COMMENT '商品名称',
    description TEXT COMMENT '商品描述',
    price DECIMAL(10, 2) NOT NULL COMMENT '商品价格',
    category_id BIGINT UNSIGNED COMMENT '分类ID',
    brand_id BIGINT UNSIGNED COMMENT '品牌ID',
    created_at TIMESTAMP NULL COMMENT '创建时间',
    updated_at TIMESTAMP NULL COMMENT '更新时间',
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL COMMENT '外键，关联分类表',
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL COMMENT '外键，关联品牌表'
) COMMENT '商品表，存储商品的基本信息';
```

2. 分类表（categories）的创建SQL：
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '分类ID',
    name VARCHAR(255) NOT NULL COMMENT '分类名称',
    parent_id BIGINT UNSIGNED NULL COMMENT '父分类ID',
    created_at TIMESTAMP NULL COMMENT '创建时间',
    updated_at TIMESTAMP NULL COMMENT '更新时间',
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE COMMENT '父分类外键，关联自身'
) COMMENT '分类表，存储商品的分类信息';
```

3. 品牌表（brands）的创建SQL：
```sql
CREATE TABLE brands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '品牌ID',
    name VARCHAR(255) NOT NULL COMMENT '品牌名称',
    created_at TIMESTAMP NULL COMMENT '创建时间',
    updated_at TIMESTAMP NULL COMMENT '更新时间'
) COMMENT '品牌表，存储商品品牌信息';
```

4. 库存表（stocks）的创建SQL：
```sql
CREATE TABLE stocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '库存ID',
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    quantity INT NOT NULL DEFAULT 0 COMMENT '库存数量',
    warehouse_location VARCHAR(255) COMMENT '仓库位置',
    created_at TIMESTAMP NULL COMMENT '创建时间',
    updated_at TIMESTAMP NULL COMMENT '更新时间',
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE COMMENT '外键，关联商品表'
) COMMENT '库存表，存储商品的库存信息';
```

5. 商品图片表（product_images）的创建SQL：
```sql
CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '商品图片ID',
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    image_url VARCHAR(255) NOT NULL COMMENT '图片URL',
    created_at TIMESTAMP NULL COMMENT '创建时间',
    updated_at TIMESTAMP NULL COMMENT '更新时间',
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE COMMENT '外键，关联商品表'
) COMMENT '商品图片表，存储商品的图片信息';
```
