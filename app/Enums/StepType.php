<?php 

namespace App\Enums;


class StepType extends Enum
{
    const NEW_OR_EXISTING_REPOSITORY = 'new-or-existing-repository'; 
    const GIT_PROVIDER = 'git-provider'; 
    const GITHUB_AUTHENTICATION = 'github-authentication'; 
    const CHOOSE_REPOSITORY = 'choose-repository'; 
    const HOSTING_PROMPT = 'hosting'; 
    const ENVIRONMENTS = 'environments'; 
    const FORGE_AUTHENTICATION = 'forge-authentication'; 
    const FORGE_SERVER_PROVIDER = 'forge-server-provider'; 
    const SERVER_CONFIGURATION = 'server-configuration'; 
}
