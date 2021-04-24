<?php

namespace App\Mail;

use App\Models\Team;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invited extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Team $team,
        public Invitation $invitation,
    )
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invited', [
            'url' => route('settings.teams.memberships.store', [
                'team' => $this->team->id,
                'token' => $this->invitation->token,
            ])
        ]);
    }
}
