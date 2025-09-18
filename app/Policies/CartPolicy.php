<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return bool
     */
    public function update(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }

    /**
     * Determine whether the user can delete the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return bool
     */
    public function delete(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }
}