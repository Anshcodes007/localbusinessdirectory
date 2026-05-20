<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $email = 'test-reg-' . uniqid() . '@example.com';
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users cannot register with numeric names', function () {
    $response = $this->post('/register', [
        'name' => 'Abhay123',
        'email' => 'test-reg-' . uniqid() . '@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['name']);
    $this->assertGuest();
});
