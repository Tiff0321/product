<a href="{{route('users.favorite',$user->id)}}">
    <strong id="following" class="stat">
        {{ count($user->favoriteProducts) }}
    </strong>
    收藏
</a>
<a href="{{route('users.purchased',$user->id)}}">
    <strong id="followers" class="stat">
        {{ count($user->purchasedProducts) }}
    </strong>
    购买
</a>
