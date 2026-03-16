<?php

namespace Tests\Testing;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedMinimalData();
    }

    private function seedMinimalData(): void
    {
        BusinessCategory::create(['name' => 'Hotels', 'slug' => 'hotels', 'sort_order' => 0]);
        SubscriptionPlan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'price' => 0,
            'duration_days' => 365,
            'is_active' => true,
        ]);
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function createBusinessUserWithBusiness(): User
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);
        $category = BusinessCategory::first();
        $business = Business::create([
            'name' => 'Test Business',
            'slug' => 'test-business',
            'business_category_id' => $category?->id,
            'status' => 'published',
            'owner_id' => $user->id,
        ]);
        $plan = SubscriptionPlan::first();
        if ($plan) {
            BusinessSubscription::create([
                'business_id' => $business->id,
                'subscription_plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'status' => 'active',
            ]);
        }
        return $user;
    }

    #[Test]
    public function home_page_returns_200(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    #[Test]
    public function login_page_returns_200_for_guest(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    #[Test]
    public function register_redirects_to_login_when_disabled(): void
    {
        $response = $this->get(route('register'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function guest_can_login(): void
    {
        $user = User::factory()->create(['role' => 'user', 'password' => bcrypt('secret123')]);
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret123',
        ]);
        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function guest_post_register_redirects_to_login_when_registration_disabled(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['email' => 'newuser@example.com']);
    }

    #[Test]
    public function authenticated_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    #[Test]
    public function public_pages_return_200(): void
    {
        $routes = [
            'about',
            'contact',
            'discover',
            'businesses',
            'places.index',
        ];
        foreach ($routes as $route) {
            $response = $this->get(route($route));
            $response->assertStatus(200, "Route [{$route}] failed.");
        }
    }

    #[Test]
    public function admin_can_access_admin_dashboard(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_access_users_index(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_access_businesses_index(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $response = $this->get(route('admin.businesses.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_access_subscriptions_index(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $response = $this->get(route('admin.subscriptions.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_access_settings(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $response = $this->get(route('admin.settings.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function non_admin_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('home'));
    }

    #[Test]
    public function business_user_can_access_dashboard_index(): void
    {
        $user = $this->createBusinessUserWithBusiness();
        $this->actingAs($user);
        $response = $this->get(route('dashboard.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function business_user_can_access_subscription_page(): void
    {
        $user = $this->createBusinessUserWithBusiness();
        $this->actingAs($user);
        $response = $this->get(route('dashboard.subscription'));
        $response->assertStatus(200);
    }

    #[Test]
    public function guest_redirected_to_login_when_visiting_dashboard(): void
    {
        $response = $this->get(route('dashboard.index'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function business_listing_show_returns_200_for_published_business(): void
    {
        $category = BusinessCategory::first();
        $business = Business::create([
            'name' => 'Public Business',
            'slug' => 'public-business',
            'business_category_id' => $category?->id,
            'status' => 'published',
        ]);
        $response = $this->get(route('businesses.show', $business->slug));
        $response->assertStatus(200);
    }

    #[Test]
    public function privacy_and_terms_pages_return_200(): void
    {
        $this->get(route('privacy'))->assertStatus(200);
        $this->get(route('terms'))->assertStatus(200);
    }
}
