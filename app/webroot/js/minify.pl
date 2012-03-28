#!/usr/bin/perl
use strict;
use warnings;

my $build = q();

while(<>) { $build .= $_; }

$build =~ s/\s*\/\*.+?\*\/\s*//msg;                           ## /* comment */
$build =~ s/\s+\/\/\s+.*?$//msg;                              ## // comment
$build =~ s/\s+/ /msg;                                        ## space
$build =~ s/ ?([\|\&\!\:\'\"\;\(\)\=\{\}\[\]+\-\,]) ?/$1/msg; ## jsminify

print $build;

exit(1);

__END__

=pod
=head1 NAME

JS Minifier - This file builds and generates Minified JS Code

=head1 VERSION

This document describes JS Minifier version 1.2.8

=head1 DESCRIPTION

Comments and whitespace removed with haste!

=head1 EXAMPLES

./jsminify.pl mycode.js > mycode.min.js

=cut

