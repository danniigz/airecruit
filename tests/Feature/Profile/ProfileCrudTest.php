<?php

namespace Tests\Feature\Profile;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_index_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.index'));

        $response->assertOk();
    }

    public function test_user_can_update_their_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'headline' => 'Desarrollador Full Stack',
            'summary' => 'Resumen profesional de prueba.',
            'phone' => '600123456',
            'location' => 'Madrid',
            'linkedin_url' => 'https://linkedin.com/in/test',
            'portfolio_url' => 'https://example.com',
        ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'headline' => 'Desarrollador Full Stack',
            'location' => 'Madrid',
        ]);
    }

    public function test_experience_can_be_created_updated_and_deleted(): void
    {
        $user = User::factory()->create();

        $storeResponse = $this->actingAs($user)->post(route('profile.experiences.store'), [
            'company' => 'Acme Corp',
            'position' => 'Backend Developer',
            'description' => 'Desarrollo de APIs.',
            'start_date' => '2020-01-01',
            'is_current' => true,
        ]);

        $storeResponse->assertRedirect(route('profile.index'));
        $storeResponse->assertSessionHasNoErrors();

        $this->assertDatabaseHas('experiences', [
            'company' => 'Acme Corp',
            'position' => 'Backend Developer',
            'is_current' => true,
            'end_date' => null,
        ]);

        $experience = Experience::firstOrFail();

        $updateResponse = $this->actingAs($user)->patch(route('profile.experiences.update', $experience), [
            'company' => 'Acme Corp',
            'position' => 'Senior Backend Developer',
            'description' => 'Desarrollo de APIs.',
            'start_date' => '2020-01-01',
            'is_current' => false,
            'end_date' => '2022-06-01',
        ]);

        $updateResponse->assertRedirect(route('profile.index'));

        $this->assertDatabaseHas('experiences', [
            'id' => $experience->id,
            'position' => 'Senior Backend Developer',
            'is_current' => false,
        ]);

        $destroyResponse = $this->actingAs($user)->delete(route('profile.experiences.destroy', $experience));

        $destroyResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseMissing('experiences', ['id' => $experience->id]);
    }

    public function test_education_can_be_created_updated_and_deleted(): void
    {
        $user = User::factory()->create();

        $storeResponse = $this->actingAs($user)->post(route('profile.educations.store'), [
            'institution' => 'Universidad Complutense',
            'degree' => 'Grado',
            'field_of_study' => 'Informática',
            'start_date' => '2015-09-01',
            'end_date' => '2019-06-30',
        ]);

        $storeResponse->assertRedirect(route('profile.index'));
        $storeResponse->assertSessionHasNoErrors();

        $education = Education::firstOrFail();

        $this->assertDatabaseHas('educations', [
            'id' => $education->id,
            'institution' => 'Universidad Complutense',
        ]);

        $updateResponse = $this->actingAs($user)->patch(route('profile.educations.update', $education), [
            'institution' => 'Universidad Complutense',
            'degree' => 'Máster',
            'field_of_study' => 'Informática',
            'start_date' => '2019-09-01',
            'end_date' => '2020-06-30',
        ]);

        $updateResponse->assertRedirect(route('profile.index'));

        $this->assertDatabaseHas('educations', [
            'id' => $education->id,
            'degree' => 'Máster',
        ]);

        $destroyResponse = $this->actingAs($user)->delete(route('profile.educations.destroy', $education));

        $destroyResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseMissing('educations', ['id' => $education->id]);
    }

    public function test_skill_can_be_created_updated_and_deleted(): void
    {
        $user = User::factory()->create();

        $storeResponse = $this->actingAs($user)->post(route('profile.skills.store'), [
            'name' => 'PHP',
            'level' => 'Avanzado',
        ]);

        $storeResponse->assertRedirect(route('profile.index'));
        $storeResponse->assertSessionHasNoErrors();

        $skill = Skill::firstOrFail();

        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'PHP']);

        $updateResponse = $this->actingAs($user)->patch(route('profile.skills.update', $skill), [
            'name' => 'Laravel',
            'level' => 'Experto',
        ]);

        $updateResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'Laravel', 'level' => 'Experto']);

        $destroyResponse = $this->actingAs($user)->delete(route('profile.skills.destroy', $skill));

        $destroyResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    public function test_language_can_be_created_updated_and_deleted(): void
    {
        $user = User::factory()->create();

        $storeResponse = $this->actingAs($user)->post(route('profile.languages.store'), [
            'name' => 'Inglés',
            'level' => 'B2',
        ]);

        $storeResponse->assertRedirect(route('profile.index'));
        $storeResponse->assertSessionHasNoErrors();

        $language = Language::firstOrFail();

        $this->assertDatabaseHas('languages', ['id' => $language->id, 'name' => 'Inglés']);

        $updateResponse = $this->actingAs($user)->patch(route('profile.languages.update', $language), [
            'name' => 'Inglés',
            'level' => 'C1',
        ]);

        $updateResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('languages', ['id' => $language->id, 'level' => 'C1']);

        $destroyResponse = $this->actingAs($user)->delete(route('profile.languages.destroy', $language));

        $destroyResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseMissing('languages', ['id' => $language->id]);
    }

    public function test_certification_can_be_created_updated_and_deleted(): void
    {
        $user = User::factory()->create();

        $storeResponse = $this->actingAs($user)->post(route('profile.certifications.store'), [
            'name' => 'AWS Certified Developer',
            'issuer' => 'Amazon',
            'issue_date' => '2022-03-15',
            'credential_url' => 'https://aws.amazon.com/verification/test',
        ]);

        $storeResponse->assertRedirect(route('profile.index'));
        $storeResponse->assertSessionHasNoErrors();

        $certification = Certification::firstOrFail();

        $this->assertDatabaseHas('certifications', ['id' => $certification->id, 'name' => 'AWS Certified Developer']);

        $updateResponse = $this->actingAs($user)->patch(route('profile.certifications.update', $certification), [
            'name' => 'AWS Certified Solutions Architect',
            'issuer' => 'Amazon',
            'issue_date' => '2023-03-15',
            'credential_url' => 'https://aws.amazon.com/verification/test',
        ]);

        $updateResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('certifications', ['id' => $certification->id, 'name' => 'AWS Certified Solutions Architect']);

        $destroyResponse = $this->actingAs($user)->delete(route('profile.certifications.destroy', $certification));

        $destroyResponse->assertRedirect(route('profile.index'));
        $this->assertDatabaseMissing('certifications', ['id' => $certification->id]);
    }

    public function test_guests_cannot_access_profile_routes(): void
    {
        $response = $this->get(route('profile.index'));

        $response->assertRedirect(route('login'));
    }
}
