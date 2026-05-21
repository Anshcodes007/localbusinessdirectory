<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function getAdmin()
{
    return User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);
}

test('admin user list only shows registered users with role user', function () {
    $admin = getAdmin();

    $user = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Registered User']);
    $owner = User::factory()->create(['role' => User::ROLE_BUSINESS_OWNER, 'name' => 'Business Owner']);
    
    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertSee('Registered User');
    $response->assertDontSee('Business Owner');
});

test('admin can update user details including password', function () {
    $admin = getAdmin();
    $user = User::factory()->create(['role' => User::ROLE_USER]);
    $newEmail = 'updatedemail-' . uniqid() . '@example.com';

    $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
        'name' => 'Updated User Name',
        'email' => $newEmail,
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $user->refresh();
    $this->assertEquals('Updated User Name', $user->name);
    $this->assertEquals($newEmail, $user->email);
    $this->assertTrue(Hash::check('NewPassword123', $user->password));
});

test('admin phone number field is validated when creating a business owner', function () {
    $admin = getAdmin();

    // 1. Phone number is required and must be 10 digits
    $response = $this->actingAs($admin)->post(route('admin.business-owners.store'), [
        'owner_name' => 'New Owner Name',
        'username' => 'newowner',
        'email' => 'newowner@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'business_name' => 'New Business',
        'city' => 'New York',
        'state' => 'NY',
        'phone' => '12345', // invalid
    ]);

    $response->assertSessionHasErrors(['phone']);

    // Create one business to occupy a phone number
    Business::create([
        'user_id' => '123',
        'name' => 'Existing Business',
        'city' => 'City',
        'state' => 'State',
        'email' => 'existing@example.com',
        'phone' => '9876543210',
    ]);

    // 2. Phone number must be unique
    $response2 = $this->actingAs($admin)->post(route('admin.business-owners.store'), [
        'owner_name' => 'New Owner Name',
        'username' => 'newowner',
        'email' => 'newowner@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'business_name' => 'New Business',
        'city' => 'New York',
        'state' => 'NY',
        'phone' => '9876543210', // duplicate
    ]);

    $response2->assertSessionHasErrors(['phone']);
});

test('names must not contain numeric values in name fields', function () {
    $admin = getAdmin();

    // 1. Business Owner Name validation
    $response = $this->actingAs($admin)->post(route('admin.business-owners.store'), [
        'owner_name' => 'Owner Name 123', // contains numbers
        'username' => 'owner123',
        'email' => 'owner123@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'business_name' => 'New Business',
        'city' => 'City',
        'state' => 'State',
        'phone' => '1234567890',
    ]);
    $response->assertSessionHasErrors(['owner_name']);

    // 2. Business Name validation
    $response2 = $this->actingAs($admin)->post(route('admin.business-owners.store'), [
        'owner_name' => 'Owner Name',
        'username' => 'owner123',
        'email' => 'owner123@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'business_name' => 'New Business 123', // contains numbers
        'city' => 'City',
        'state' => 'State',
        'phone' => '1234567890',
    ]);
    $response2->assertSessionHasErrors(['business_name']);
});

test('admin cannot update role of a user', function () {
    $admin = getAdmin();
    $user = User::factory()->create(['role' => User::ROLE_USER]);
    $newEmail = 'updatedemail-' . uniqid() . '@example.com';

    $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
        'name' => 'Updated User Name',
        'email' => $newEmail,
        'role' => 'admin', // trying to change role to admin
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $user->refresh();
    $this->assertEquals(User::ROLE_USER, $user->role); // role must remain user
});

test('admin cannot access edit, update, or delete of a user who is not of role user', function () {
    $admin = getAdmin();
    
    // Creating an admin user to try and update/delete
    $otherAdmin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    // Creating a business owner
    $owner = User::factory()->create(['role' => User::ROLE_BUSINESS_OWNER]);

    // Check edit route returns 404 for admin/owner
    $this->actingAs($admin)->get(route('admin.users.edit', $otherAdmin))->assertStatus(404);
    $this->actingAs($admin)->get(route('admin.users.edit', $owner))->assertStatus(404);

    // Check update route returns 404
    $this->actingAs($admin)->put(route('admin.users.update', $otherAdmin), [
        'name' => 'Hack Name',
        'email' => 'hack@example.com',
    ])->assertStatus(404);

    // Check delete route returns 404
    $this->actingAs($admin)->delete(route('admin.users.destroy', $otherAdmin))->assertStatus(404);
});
