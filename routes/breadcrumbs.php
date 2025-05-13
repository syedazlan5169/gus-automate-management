<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('/', function (BreadcrumbTrail $trail) {
    $user = auth()->user();
    
    $route = route(
        $user && $user->role !== 'customer' ? 'admin.dashboard' : 'client.dashboard'
    );

    $trail->push('Home', $route);
});

// Home > Finance
Breadcrumbs::for('finance', function (BreadcrumbTrail $trail) {
    $trail->parent('/');
    $trail->push('Finance', route('finance.index'));
});

// Home > Booking 
Breadcrumbs::for('booking', function (BreadcrumbTrail $trail) {
    $trail->parent('/');
    $trail->push('Bookings', route('bookings.index'));
});
// Home > Booking > Create 
Breadcrumbs::for('booking.create', function (BreadcrumbTrail $trail) {
    $trail->parent('booking');
    $trail->push('Create', route('booking.create'));
});
// Home > Booking > Show
Breadcrumbs::for('booking.show', function (BreadcrumbTrail $trail, $booking) {
    $trail->parent('booking');
    $trail->push($booking->booking_number, route('booking.show', $booking->id));
});
// Home > Booking > Edit
Breadcrumbs::for('booking.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('booking');
    $trail->push('Edit', route('booking.edit', $id));
});
// Home > Booking > Create Shipping Instruction
Breadcrumbs::for('booking.create-shipping-instruction', function (BreadcrumbTrail $trail, $booking) {
    $trail->parent('booking.show', $booking);
    $trail->push('Create Shipping Instruction', route('shipping-instructions.create', $booking->id));
});
// Home > Booking > Show > Edit Shipping Instruction
Breadcrumbs::for('shipping-instructions.show', function (BreadcrumbTrail $trail, $shippingInstruction) {
    $trail->parent('booking.show', $shippingInstruction->booking);
    $trail->push('Edit Shipping Instruction', route('shipping-instructions.show', $shippingInstruction->id));
});

// Home > Routes 
Breadcrumbs::for('routes', function (BreadcrumbTrail $trail) {
    $trail->parent('/');
    $trail->push('Routes', route('shipping-routes.index'));
});
// Home > Routes > Create
Breadcrumbs::for('routes.create', function (BreadcrumbTrail $trail) {
    $trail->parent('routes');
    $trail->push('Create Route', route('shipping-routes.create'));
});
// Home > Routes > Edit
Breadcrumbs::for('routes.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('routes');
    $trail->push('Edit Route', route('shipping-routes.edit', $id));
});

// Home > Users 
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('/');
    $trail->push('Users', route('users.index'));
});
// Home > Users > Create
Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push('Create User', route('users.create'));
});
// Home > Users > Show
Breadcrumbs::for('users.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('users');
    $trail->push('Edit User', route('users.show', $id));
});




