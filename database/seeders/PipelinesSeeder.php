<?php

namespace Database\Seeders;

use App\Enums\StepType;
use App\Models\Auth\User;
use App\Enums\PipelineStatus;
use App\Models\Pipeline\Step;
use Illuminate\Database\Seeder;
use App\Models\Pipeline\Account;
use App\Models\Pipeline\Pipeline;
use App\Models\Pipeline\StepConfiguration;

class PipelinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'test@example.com')->first();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'identifier' => env('GITHUB_ACCOUNT_NAME'),
            'email' => env('GITHUB_ACCOUNT_EMAIL'),
            'type' => 'github',
            'token' => env('GITHUB_ACCOUNT_TOKEN'),
        ]);

        $successfulPipeline = Pipeline::factory()->create([
            'name' => 'Cool project',
            'team_id' => $user->currentTeam->id,
        ]);
        $failedPipeline = Pipeline::factory()->create([
            'name' => 'Lame project',
            'team_id' => $user->currentTeam->id,
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'pipeline_id' => $successfulPipeline->id,
            'details' => [
                'account_id' => 'new', 
            ],
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::NEW_OR_EXISTING_REPOSITORY,
            'pipeline_id' => $failedPipeline->id,
            'details' => [
                'account_id' => 'new', 
            ],
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::GIT_PROVIDER,
            'pipeline_id' => $successfulPipeline->id,
            'details' => [
                'account_id' => 'github', 
            ],
        ]);

        StepConfiguration::factory()->create([
            'type' => StepType::GIT_PROVIDER,
            'pipeline_id' => $failedPipeline->id,
            'details' => [
                'account_id' => 'github', 
            ],
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::SUCCESSFUL,
            'title' => 'Create repository',
            'config_id' => StepConfiguration::factory()->create([
                'type' => StepType::GITHUB_AUTHENTICATION,
                'pipeline_id' => $successfulPipeline->id,
                'details' => [
                    'account_id' => $account->id, 
                ],
            ]),
        ]);

        Step::factory()->create([
            'status' => PipelineStatus::FAILED,
            'title' => 'Create repository',
            'config_id' => StepConfiguration::factory()->create([
                'type' => StepType::GITHUB_AUTHENTICATION,
                'pipeline_id' => $failedPipeline->id,
                'details' => [
                    'account_id' => $account->id, 
                ],
            ]),
        ]);

    }
}
