<?php

namespace Tests\Feature\Ownership;

use App\Models\Certification;
use App\Models\Comparison;
use App\Models\CoverLetter;
use App\Models\Cv;
use App\Models\Education;
use App\Models\Experience;
use App\Models\JobOffer;
use App\Models\Language;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifica que un usuario no pueda ver, editar ni borrar datos de otro
 * usuario: CVs, ofertas, comparaciones, cartas de presentación y los
 * distintos elementos del perfil profesional.
 */
class OwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_cv(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->get(route('cvs.show', $cv));

        $response->assertForbidden();
    }

    public function test_user_cannot_analyze_another_users_cv(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->postJson(route('api.cvs.analyze', $cv));

        $response->assertForbidden();
    }

    public function test_user_cannot_view_another_users_job_offer(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $jobOffer = JobOffer::factory()->for($owner)->create();

        $this->actingAs($intruder)->get(route('job-offers.show', $jobOffer))->assertForbidden();
        $this->actingAs($intruder)->get(route('job-offers.edit', $jobOffer))->assertForbidden();
    }

    public function test_user_cannot_update_or_delete_another_users_job_offer(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $jobOffer = JobOffer::factory()->for($owner)->create();

        $this->actingAs($intruder)->patch(route('job-offers.update', $jobOffer), [
            'title' => 'Hackeado',
            'company' => 'Evil Corp',
            'description' => 'Descripción manipulada.',
        ])->assertForbidden();

        $this->actingAs($intruder)->delete(route('job-offers.destroy', $jobOffer))->assertForbidden();

        $this->assertDatabaseHas('job_offers', ['id' => $jobOffer->id, 'title' => $jobOffer->title]);
    }

    public function test_user_cannot_view_another_users_comparison(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $comparison = Comparison::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->get(route('comparisons.show', $comparison));

        $response->assertForbidden();
    }

    public function test_user_cannot_create_comparison_using_another_users_cv_or_job_offer(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();
        $jobOffer = JobOffer::factory()->for($owner)->create();

        // El CV pertenece a otro usuario, la oferta es propia.
        $ownJobOffer = JobOffer::factory()->for($intruder)->create();
        $this->actingAs($intruder)->postJson(route('api.comparisons.store'), [
            'cv_id' => $cv->id,
            'job_offer_id' => $ownJobOffer->id,
        ])->assertForbidden();

        // La oferta pertenece a otro usuario, el CV es propio.
        $ownCv = Cv::factory()->for($intruder)->create();
        $this->actingAs($intruder)->postJson(route('api.comparisons.store'), [
            'cv_id' => $ownCv->id,
            'job_offer_id' => $jobOffer->id,
        ])->assertForbidden();

        $this->assertDatabaseCount('comparisons', 0);
    }

    public function test_user_cannot_view_another_users_cover_letter(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $coverLetter = CoverLetter::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->get(route('cover-letters.show', $coverLetter));

        $response->assertForbidden();
    }

    public function test_user_cannot_create_cover_letter_using_another_users_job_offer(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $jobOffer = JobOffer::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->postJson(route('api.cover-letters.store'), [
            'job_offer_id' => $jobOffer->id,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('cover_letters', 0);
    }

    public function test_user_cannot_modify_another_users_profile_items(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $profile = Profile::factory()->for($owner)->create();

        $experience = Experience::factory()->for($profile)->create();
        $education = Education::factory()->for($profile)->create();
        $skill = Skill::factory()->for($profile)->create();
        $language = Language::factory()->for($profile)->create();
        $certification = Certification::factory()->for($profile)->create();

        $this->actingAs($intruder)->patch(route('profile.experiences.update', $experience), [
            'company' => 'Hackeado', 'position' => 'X', 'start_date' => '2020-01-01', 'is_current' => true,
        ])->assertForbidden();
        $this->actingAs($intruder)->delete(route('profile.experiences.destroy', $experience))->assertForbidden();

        $this->actingAs($intruder)->patch(route('profile.educations.update', $education), [
            'institution' => 'Hackeado', 'degree' => 'X', 'start_date' => '2020-01-01',
        ])->assertForbidden();
        $this->actingAs($intruder)->delete(route('profile.educations.destroy', $education))->assertForbidden();

        $this->actingAs($intruder)->patch(route('profile.skills.update', $skill), [
            'name' => 'Hackeado',
        ])->assertForbidden();
        $this->actingAs($intruder)->delete(route('profile.skills.destroy', $skill))->assertForbidden();

        $this->actingAs($intruder)->patch(route('profile.languages.update', $language), [
            'name' => 'Hackeado',
        ])->assertForbidden();
        $this->actingAs($intruder)->delete(route('profile.languages.destroy', $language))->assertForbidden();

        $this->actingAs($intruder)->patch(route('profile.certifications.update', $certification), [
            'name' => 'Hackeado',
        ])->assertForbidden();
        $this->actingAs($intruder)->delete(route('profile.certifications.destroy', $certification))->assertForbidden();

        $this->assertDatabaseHas('experiences', ['id' => $experience->id, 'company' => $experience->company]);
        $this->assertDatabaseHas('educations', ['id' => $education->id, 'institution' => $education->institution]);
        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => $skill->name]);
        $this->assertDatabaseHas('languages', ['id' => $language->id, 'name' => $language->name]);
        $this->assertDatabaseHas('certifications', ['id' => $certification->id, 'name' => $certification->name]);
    }

    public function test_users_only_see_their_own_data_in_index_views(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Cv::factory()->for($other)->create();
        JobOffer::factory()->for($other)->create();
        Comparison::factory()->for($other)->create();
        CoverLetter::factory()->for($other)->create();

        $cv = Cv::factory()->for($user)->create();
        $jobOffer = JobOffer::factory()->for($user)->create();
        $comparison = Comparison::factory()->for($user)->create();
        $coverLetter = CoverLetter::factory()->for($user)->create();

        $this->actingAs($user)->get(route('cvs.index'))
            ->assertOk()
            ->assertSee($cv->original_filename);

        $this->actingAs($user)->get(route('job-offers.index'))
            ->assertOk()
            ->assertSee($jobOffer->title);

        $this->actingAs($user)->get(route('comparisons.index'))->assertOk();
        $this->actingAs($user)->get(route('cover-letters.index'))->assertOk();
    }
}
