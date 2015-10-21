<?php

namespace spec\Gaufrette;

use PhpSpec\ObjectBehavior;

class FilesystemMapSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Gaufrette\FilesystemMap');
    }

    /**
     * @param Gaufrette\Filesystem $filesystem
     */
    function it_checks_if_has_mapped_filesystem($filesystem)
    {
        $this->set('some', $filesystem);
        $this->has('some')->shouldReturn(true);
        $this->has('other')->shouldReturn(false);
    }

    /**
     * @param Gaufrette\Filesystem $filesystem
     */
    function it_sets_mapped_filesystem($filesystem)
    {
        $this->set('some', $filesystem);
        $this->get('some')->shouldReturn($filesystem);
    }

    function it_fails_when_get_filesystem_which_was_not_mapped()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('There is no filesystem defined for the "some" domain.'))
            ->duringGet('some')
        ;
    }

    /**
     * @param Gaufrette\Filesystem $filesystem
     */
    function it_removes_mapped_filesystem($filesystem)
    {
        $this->set('some', $filesystem);
        $this->remove('some');

        $this->has('some')->shouldReturn(false);
    }

    function it_fails_when_try_to_remove_filesystem_which_was_not_mapped()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('Cannot remove the "some" filesystem as it is not defined.'))
            ->duringRemove('some')
        ;
    }

    /**
     * @param Gaufrette\Filesystem $filesystem
     */
    function it_removes_all_filesystems($filesystem)
    {
        $this->set('some', $filesystem);
        $this->set('other', $filesystem);
        $this->clear();

        $this->has('some')->shouldReturn(false);
        $this->has('other')->shouldReturn(false);
        $this->all()->shouldReturn(array());
    }
}
