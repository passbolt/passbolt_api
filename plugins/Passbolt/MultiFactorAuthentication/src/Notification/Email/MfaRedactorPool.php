<?php

namespace Passbolt\MultiFactorAuthentication\Notification\Email;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;

class MfaRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    public function getSubscribedRedactors()
    {
        return [
            new MfaUserSettingsResetEmailRedactor()
        ];
    }
}
