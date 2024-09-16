这是一个简单的laravel商品项目
以下记录项目创建流程
1.用户功能开发
2.产品功能开发
创建数据迁移文件
php artisan make:migration create_stocks_table
php artisan make:migration create_product_images_table
php artisan make:migration create_categories_table
php artisan make:migration create_brands_table
php artisan make:migration create_products_table
php artisan migrate
创建数据模型
php artisan make:model Category
php artisan make:model Brand
php artisan make:model Product
创建仓库和种子
php artisan make:factory CategoryFactory
php artisan make:seeder CategorySeeder
php artisan make:factory ProductFactory
php artisan make:seeder ProductSeeder
php artisan make:factory BrandFactory
php artisan make:seeder BrandSeeder
生成数据
php artisan db:seed
php artisan db:seed --class=BrandSeeder
php artisan db:seed --class=ProductSeeder

php artisan make:policy ProductPolicy

购买库存表减一是通过数据库的触发器实现的
CREATE TRIGGER update_stock_after_purchase
AFTER INSERT ON user_product
FOR EACH ROW
BEGIN
IF NEW.type = 'purchased' THEN
UPDATE stocks
SET quantity = quantity - 1
WHERE product_id = NEW.product_id;
END IF;
END


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
