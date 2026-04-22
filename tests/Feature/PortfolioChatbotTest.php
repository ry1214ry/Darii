<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioChatbotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_chatbot_returns_skill_answers_from_portfolio_data(): void
    {
        Setting::create([
            'site_name' => 'Dary Portfolio',
            'contact_email' => 'hello@example.com',
        ]);

        Profile::create([
            'full_name' => 'Roeun Dary',
            'job_title' => 'Laravel Developer',
            'technologies' => 'Laravel, PHP, MySQL, JavaScript',
        ]);

        Skill::create([
            'skill_name' => 'Laravel',
            'percentage' => 95,
            'status' => 1,
            'sort_order' => 1,
        ]);

        Skill::create([
            'skill_name' => 'MySQL',
            'percentage' => 88,
            'status' => 1,
            'sort_order' => 2,
        ]);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'What skills are highlighted?',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('topic', 'skills');

        $this->assertStringContainsString('Laravel', $response->json('answer'));
        $this->assertStringContainsString('MySQL', $response->json('answer'));
    }

    public function test_chatbot_can_answer_about_a_specific_project(): void
    {
        Project::create([
            'title' => 'Dary Portfolio Platform',
            'slug' => 'dary-portfolio-platform',
            'description' => 'A portfolio website with an admin dashboard and dynamic content sections.',
            'technology_used' => 'Laravel, Blade, MySQL',
            'status' => 1,
            'is_featured' => 1,
        ]);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Tell me about Dary Portfolio Platform',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('topic', 'project-detail');

        $this->assertStringContainsString('Dary Portfolio Platform', $response->json('answer'));
        $this->assertStringContainsString('Laravel, Blade, MySQL', $response->json('answer'));
    }

    public function test_chatbot_returns_contact_email_when_available(): void
    {
        Setting::create([
            'site_name' => 'Dary Portfolio',
            'contact_email' => 'contact@dary.test',
        ]);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'What is the contact email?',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('topic', 'email');

        $this->assertStringContainsString('contact@dary.test', $response->json('answer'));
    }

    public function test_chatbot_falls_back_for_unknown_questions(): void
    {
        Setting::create([
            'site_name' => 'Dary Portfolio',
        ]);

        Service::create([
            'title' => 'Web Development',
            'short_description' => 'Builds maintainable Laravel websites.',
            'status' => 1,
        ]);

        Blog::create([
            'title' => 'Launching the Portfolio',
            'slug' => 'launching-the-portfolio',
            'short_description' => 'An overview of the new site.',
            'status' => 1,
            'published_at' => now(),
        ]);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Do you like pizza?',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('topic', 'fallback');

        $this->assertStringContainsString('Dary Portfolio', $response->json('answer'));
    }
}
