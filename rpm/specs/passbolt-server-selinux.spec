%global selinuxtype	targeted
%global moduletype	services
%global modulenames	passbolt-server

# Usage: _format var format
#   Expand 'modulenames' into various formats as needed
#   Format must contain '$x' somewhere to do anything useful
%global _format() export %1=""; for x in %{modulenames}; do %1+=%2; %1+=" "; done;

# Relabel files
%global relabel_files() \ # ADD files in *.fc file


# Version of distribution SELinux policy package 
%global selinux_policyver 3.13.1-128.6.fc22

# Package information
Name:			passbolt-server-selinux
Version:		%{_passbolt_selinux_version}
Release:		1%{?dist}
License:		GPLv2
Group:			System Environment/Base
Summary:		SELinux Policies for Passbolt
BuildArch:		noarch
URL:			https://www.passbolt.com
Requires(post):		selinux-policy-base >= %{selinux_policyver}, selinux-policy-targeted >= %{selinux_policyver}, policycoreutils, %{_policycoreutils_python}, libselinux-utils
BuildRequires:		selinux-policy selinux-policy-devel

Source:			%{name}-%{version}.tar.gz

%description
SELinux policy modules for use with Passbolt

%prep
%setup -q

%build
make SHARE="%{_datadir}" TARGETS="%{modulenames}"

%install

# Install SELinux interfaces
%_format INTERFACES $x.if
install -d %{buildroot}%{_datadir}/selinux/devel/include/%{moduletype}
install -p -m 644 $INTERFACES \
	%{buildroot}%{_datadir}/selinux/devel/include/%{moduletype}

# Install policy modules
%_format MODULES $x.pp.bz2
install -d %{buildroot}%{_datadir}/selinux/packages
install -m 0644 $MODULES \
	%{buildroot}%{_datadir}/selinux/packages

%post
#
# Install all modules in a single transaction
#
%_format MODULES %{_datadir}/selinux/packages/$x.pp.bz2
%{_sbindir}/semodule -n -s %{selinuxtype} -i $MODULES
if %{_sbindir}/selinuxenabled ; then
    %{_sbindir}/load_policy
    %relabel_files
fi


%postun
if [ $1 -eq 0 ]; then
	%{_sbindir}/semodule -n -r %{modulenames} &> /dev/null || :
	if %{_sbindir}/selinuxenabled ; then
		%{_sbindir}/load_policy
		%relabel_files
	fi
fi

%files
%defattr(-,root,root,0755)
%attr(0644,root,root) %{_datadir}/selinux/packages/*.pp.bz2
%attr(0644,root,root) %{_datadir}/selinux/devel/include/%{moduletype}/*.if

%changelog
* Fri Mar 06 2015 Lukas Vrabec <lvrabec@redhat.com> - 0.1.0-1
- First Build
