<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.14.0
 */

namespace Passbolt\EmailDigest\Test\TestCase\Utility\Marshaller\Type;

use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailPreview;
use Passbolt\EmailDigest\Utility\Marshaller\Type\ByTemplateAndOperatorDigestMarshaller;
use PHPUnit\Framework\MockObject\MockObject;

class ByTemplateAndOperatorDigestMarshallerTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var ByTemplateAndOperatorDigestMarshaller
     */
    private $sut;

    /**
     * @var MockObject|EmailPreviewFactory
     */
    private $emailPreviewFactoryMock;

    public function setUp()
    {
        $this->emailPreviewFactoryMock = $this->createMock(EmailPreviewFactory::class);

        $this->sut = new ByTemplateAndOperatorDigestMarshaller('Test subject', 'user', $this->emailPreviewFactoryMock);

        parent::setUp();
    }

    /**
     * @dataProvider provideEmailEntitiesSupportedEmailTemplates
     * @param Entity $email An email entity
     * @param array $supportedTemplates A list of supported templates (can be empty)
     */
    public function testThatCanMarshalDigestsReturnTrueWhenEmailTemplateIsSupported(Entity $email, array $supportedTemplates)
    {
        $this->emailPreviewFactoryMock->expects($this->any())
            ->method('renderEmailPreviewFromEmailEntity')
            ->willReturn(new EmailPreview('Headers', 'Body'));

        // Add the supported templates to the marshaller
        foreach ($supportedTemplates as $supportedTemplate) {
            $this->sut->addSupportedTemplate($supportedTemplate);
        }

        $this->assertTrue($this->sut->canMarshalDigestsFrom($email));
    }

    public function provideEmailEntitiesSupportedEmailTemplates()
    {
        $users = [
            'ada' => $this->createUserForEmail('ada@passbolt.com', 'Ada Lovelace'),
        ];

        return [
            "With list of supported templates non empty, and same template defined for email, `canMarshalDigestsFrom` should return true" => [
                $this->createEmailQueueEntity(['template' => 'supportedTemplate.ctp'], ['user' => $users['ada']]),
                [
                    'supportedTemplate.ctp',
                ],
            ],
            "With list of supported templates empty, and template defined for email, `canMarshalDigestsFrom` should return true" => [
                $this->createEmailQueueEntity(['template' => 'supportedTemplate.ctp'], ['user' => $users['ada']]),
                [],
            ],
        ];
    }

    /**
     * @dataProvider provideEmailEntitiesWithUnsupportedEmailTemplates
     * @param Entity $email An email entity
     * @param array $supportedTemplates A list of supported templates can be empty.
     */
    public function testThatCanMarshalDigestsReturnFalseWhenEmailTemplateIsNotSupported(Entity $email, array $supportedTemplates)
    {
        $this->emailPreviewFactoryMock->expects($this->any())
            ->method('renderEmailPreviewFromEmailEntity')
            ->willReturn(new EmailPreview('Headers', 'Body'));

        // Add the supported templates
        foreach ($supportedTemplates as $supportedTemplate) {
            $this->sut->addSupportedTemplate($supportedTemplate);
        }

        $this->assertFalse($this->sut->canMarshalDigestsFrom($email));
    }

    public function provideEmailEntitiesWithUnsupportedEmailTemplates()
    {
        $users = [
            'ada' => $this->createUserForEmail('ada@passbolt.com', 'Ada Lovelace'),
        ];

        return [
            "With list of supported templates non empty, and a different template defined for email, `canMarshalDigestsFrom` should return false" => [
                $this->createEmailQueueEntity(['template' => 'unsupportedTemplate.ctp'], ['user' => $users['ada']]),
                [
                    'supportedTemplate.ctp',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideEmailEntityNotSupported
     * @param Entity $email Email entity
     * @param array $supportedTemplates Supported templates list
     * @throws UnsupportedEmailDigestDataException
     */
    public function testAddEmailEntityDataThrowUnsupportedExceptionIfProvidedEmailIsNotSupported(Entity $email, array $supportedTemplates)
    {
        $this->emailPreviewFactoryMock->expects($this->any())
            ->method('renderEmailPreviewFromEmailEntity')
            ->willReturn(new EmailPreview('Headers', 'Body'));

        // Add the supported templates
        foreach ($supportedTemplates as $supportedTemplate) {
            $this->sut->addSupportedTemplate($supportedTemplate);
        }

        $this->expectException(UnsupportedEmailDigestDataException::class);

        $this->sut->addEmailEntityToMarshal($email);
    }

    public function provideEmailEntityNotSupported()
    {
        $users = [
            'ada' => $this->createUserForEmail('ada@passbolt.com', 'Ada Lovelace'),
        ];

        return [
            "With an email with an unsupported template" => [
                $this->createEmailQueueEntity(['template' => 'unsupportedTemplate.ctp'], ['user' => $users['ada']]),
                [
                    'supportedTemplate.ctp',
                ],
            ],
            "With an email without executed by user in template vars" => [
                $this->createEmailQueueEntity(['template' => 'supportedTemplate.ctp'], []),
                [
                    'supportedTemplate.ctp',
                ],
            ],
        ];
    }

    /**
     * @return void
     * @throws UnsupportedEmailDigestDataException
     */
    public function testThatAddEntityEmailToMarshallAddEmailIfSupportedAndMarshallDigestsReturnADigestFromIt()
    {
        $emails = [
            $this->createEmailQueueEntity([], ['user' => $this->createUserForEmail('ada@passbolt.com', 'Ada Lovelace')]),
        ];

        $this->emailPreviewFactoryMock->expects($this->any())
            ->method('renderEmailPreviewFromEmailEntity')
            ->willReturn(new EmailPreview('Headers', 'Body'));

        $this->assertEmpty($this->sut->marshalDigests());

        foreach ($emails as $email) {
            $this->sut->addEmailEntityToMarshal($email);
        }

        $this->assertNotEmpty($this->sut->marshalDigests());
    }

    /**
     * @dataProvider provideEmailEntitiesWithExpectedDigestCount
     * @param Entity[] $emails An array of email entities from emailqueue
     * @param int $expectedDigestsCount Count of expected digests
     * @return void
     * @throws UnsupportedEmailDigestDataException
     */
    public function testThatMarshalDigestsProduceADigestByExecutedByUser(array $emails, int $expectedDigestsCount)
    {
        $this->emailPreviewFactoryMock->expects($this->any())
            ->method('renderEmailPreviewFromEmailEntity')
            ->willReturn(new EmailPreview('Headers', 'Body'));

        foreach ($emails as $email) {
            $this->sut->addEmailEntityToMarshal($email);
        }

        $digests = $this->sut->marshalDigests();

        $this->assertCount($expectedDigestsCount, $digests);
    }

    /**
     * Return arrays formatted as follow:
     * - Emails entities
     * - Expected digests count
     * @return array
     * @see testThatMarshalDigestsProduceADigestByExecutedByUser
     */
    public function provideEmailEntitiesWithExpectedDigestCount()
    {
        $users = [
            'ada' => $this->createUserForEmail('ada@passbolt.com', 'Ada Lovelace'),
            'betty' => $this->createUserForEmail('betty@passbolt.com', 'Betty Holberton'),
        ];

        return [
            "with a different executed by user for each email, it should return an array with 2 digests" => [
                [
                    $this->createEmailQueueEntity([], ['user' => $users['ada']]),
                    $this->createEmailQueueEntity([], ['user' => $users['betty']]),
                ],
                2,
            ],
            "with the same executed by user for each email, it should return an array with 1 digest" => [
                [
                    $this->createEmailQueueEntity([], ['user' => $users['ada']]),
                    $this->createEmailQueueEntity([], ['user' => $users['ada']]),
                ],
                1,
            ],
        ];
    }

    /**
     * @dataProvider provideEmailEntitiesWithTheirPreviewAndExpectedDigestContent
     * @param array $emails An array of emails entities
     * @param array $previews An array of previews
     * @param string $expectedDigestContent Expected digest content
     * @return void
     * @throws UnsupportedEmailDigestDataException
     */
    public function testThatMarshalDigestsCreateDigestWithContentComposedFromPreviewOfEachEmails(array $emails, array $previews, string $expectedDigestContent)
    {
        $this->emailPreviewFactoryMock->expects($this->any())
            ->method('renderEmailPreviewFromEmailEntity')
            ->withConsecutive(...$emails)
            ->willReturnOnConsecutiveCalls(...$previews);

        foreach ($emails as $email) {
            $this->sut->addEmailEntityToMarshal($email);
        }

        $digests = $this->sut->marshalDigests();

        $this->assertSame($expectedDigestContent, $digests[0]->getContent());
    }

    /**
     * Return arrays formatted as follow:
     * - Emails entities
     * - Emails previews
     * - Expected digest content
     * @return array
     * @see testThatMarshalDigestsCreateDigestWithContentComposedFromPreviewOfEachEmails
     */
    public function provideEmailEntitiesWithTheirPreviewAndExpectedDigestContent()
    {
        $users = [
            'ada' => $this->createUserForEmail('ada@passbolt.com', 'Ada Lovelace'),
            'betty' => $this->createUserForEmail('betty@passbolt.com', 'Betty Holberton'),
        ];

        return [
            "With 2 emails, their content should be concatenated and be the content of the digest" => [
                [
                    $this->createEmailQueueEntity([], ['user' => $users['ada']]),
                    $this->createEmailQueueEntity([], ['user' => $users['ada']]),
                ],
                [
                    new EmailPreview('Headers', '[Body1]'),
                    new EmailPreview('Headers', '[Body2]'),
                ],
                '[Body1][Body2]',
            ],
            "with 1 email, its content should be the content of the digest" => [
                [
                    $this->createEmailQueueEntity([], ['user' => $users['ada']]),
                ],
                [
                    new EmailPreview('Headers', '[Body1]'),
                ],
                '[Body1]',
            ],
        ];
    }
}
