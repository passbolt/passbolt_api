#
# Cookbook:: docker_test
# Recipe:: passbolt_package_build
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

execute 'what is there where we are' do
  cwd     "#{node['dest_dir']}"
  command 'ls -l && pwd'
  live_stream true
  action :run
end

if platform_family?('rhel', 'suse', 'fedora')
  file '/etc/resolv.conf' do
    content 'nameserver 1.1.1.1'
    mode '0644'
    owner 'root'
    group 'root'
  end
end

if platform_family?('debian')
  if (Dir.glob("#{node['dest_dir']}/passbolt-*.deb").empty? or
      Dir.glob("#{node['dest_dir']}/vendor/*").empty?) then
    apt_update 'all platforms' do
      action :update
    end
  end
end

if platform_family?('rhel', 'fedora') then
  package 'RHEL: Install dependencies' do
    package_name ['bc']
    action :install
  end
  execute "Setup PHP repository" do
    cwd     "#{node['dest_dir']}"
    command  "/bin/sh rpm/scripts/setup-php-#{node['platform_family']}.sh"
    action   :run
  end
end

if platform_family?('suse') then
  package 'SUSE: Install curl' do
    package_name ['curl']
    action :install
  end
  execute "Setup PHP repository" do
    cwd     "#{node['dest_dir']}"
    command  "/bin/sh rpm/scripts/setup-php-#{node['platform_family']}.sh"
    action   :run
  end
  execute "Setup PHP repository" do
    cwd     "#{node['dest_dir']}"
    command  "zypper refresh && zypper update -y"
    action   :run
  end
  package 'SUSE: Install dependencies' do
    package_name ['php7-cli', 'php7-zip', 'php7-json', 'wget', 'unzip']
    action :install
  end
  package 'SUSE: Install composer' do
    package_name ['php-composer']
    action :install
  end
end

if Dir.glob("#{node['dest_dir']}/vendor/*").empty? then
  if platform_family?('debian') then
    package 'Debian: Install composer' do
      package_name 'composer'
      action :install
    end
  elsif platform_family?('rhel', 'fedora') then
    package 'RHEL: Install composer dependencies' do
      flush_cache [ :before ]
      package_name ['php-cli', 'php-zip', 'php-json', 'wget', 'unzip']
      action :install
    end
    if platform_family?('fedora') then
      package 'Ensure pcre2 is last version' do
        flush_cache [ :before ]
        package_name ['pcre2']
        action :upgrade
      end
    end
    execute 'RHEL: Install composer' do
      cwd     "#{node['dest_dir']}"
      command "/bin/sh rpm/scripts/setup-composer.sh"
      action :run
    end
  end
  execute 'Download vendors' do
    cwd     "#{node['dest_dir']}"
    command 'composer install -o --prefer-dist --no-dev --ignore-platform-reqs --no-interaction'
    action :run
  end
end

if platform_family?('debian')
  if Dir.glob("#{node['dest_dir']}/passbolt-*.deb").empty? then
    package 'Install dev dependencies' do
      package_name ['devscripts', 'build-essential', 'apt-utils', 'fakeroot', 'equivs', 'cdbs', 'git-buildpackage']
      action :install
    end

    execute 'Build debian package' do
      cwd     "#{node['dest_dir']}"
      command "export PASSBOLT_FLAVOUR=#{node['passbolt_flavour']} \
               && make -f debian/rules debian/control \
               && gbp dch --snapshot --snapshot-number=$(date +%s) --ignore-branch >/dev/null 2>&1 \
               && mk-build-deps -irt'apt-get --no-install-recommends -yV' debian/control && dpkg-checkbuilddeps \
               && debuild --preserve-envvar PASSBOLT_FLAVOUR --preserve-envvar PASSBOLT_COMPONENT -us -uc -b -i -I  \
               && cp ../*.deb . \
               && cp ../*.build . \
               && cp ../*.buildinfo . \
               && cp ../*.changes ."
      action :run
    end
  end
elsif platform_family?('rhel', 'suse', 'fedora')
  if Dir.glob("#{node['dest_dir']}/passbolt-*.rpm").empty? then
    if platform_family?('suse')
      package 'SUSE: Install dev dependencies' do
        package_name ['rpmdevtools', 'rpmlint', 'rsync', 'rpm-build']
        action :install
      end
    elsif platform_family?('rhel', 'fedora')
      package 'RHEL: Install dev dependencies' do
        package_name ['rpmdevtools', 'rpmlint', 'rsync', 'selinux-policy-devel', 'rpm-build']
        action :install
      end
    end

    execute 'RHEL: List' do
      cwd     "#{node['dest_dir']}"
      command "ls -las"
      live_stream true
      action :run
    end

    execute 'RHEL: Setup RPM devtree' do
      cwd     "#{node['dest_dir']}"
      command "rpmdev-setuptree"
      action :run
    end

    execute 'RHEL: Build Passbolt RPM package' do
      cwd     "#{node['dest_dir']}"
      command "OS_FAMILY=#{node['platform_family']} \
                PASSBOLT_FLAVOUR=#{node['passbolt_flavour']} \
                PKG_VERSION=#{node['passbolt_version']} \
                /bin/sh rpm/scripts/build-passbolt-server.sh"
      action :run
    end

    # No passbolt-selinux build for suse
    if platform_family?('rhel', 'fedora')
      execute 'RHEL: Build passbolt-selinux RPM package' do
        cwd     "#{node['dest_dir']}"
        command "PKG_VERSION=0.1 /bin/sh rpm/scripts/build-passbolt-selinux.sh"
        action :run
      end
    end

    execute 'RHEL: Copy packages in repository' do
      cwd     "#{node['dest_dir']}"
      command "cp -rf ~/rpmbuild/RPMS/noarch/passbolt-* ."
      action :run
    end
  end
end
