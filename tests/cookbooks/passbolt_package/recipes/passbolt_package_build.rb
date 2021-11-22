#
# Cookbook:: docker_test
# Recipe:: passbolt_package_build
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

if platform_family?('rhel') && node['platform_version'].to_f < 8.0
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

if Dir.glob("#{node['dest_dir']}/vendor/*").empty? then
  if platform_family?('debian') then
    package 'Debian: Install composer' do
      package_name 'composer'
      action :install
    end
  elsif platform_family?('rhel') then
    package 'RHEL: Install composer dependencies' do
      package_name ['php-cli', 'php-zip', 'php-json', 'wget', 'unzip']
      action :install
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
               && gbp dch --snapshot --snapshot-number=$(date +%s) --ignore-branch \
               && mk-build-deps -irt'apt-get --no-install-recommends -yV' debian/control && dpkg-checkbuilddeps \
               && debuild --preserve-envvar PASSBOLT_FLAVOUR -us -uc -b -i -I  \
               && cp ../*.deb . \
               && cp ../*.build . \
               && cp ../*.buildinfo . \
               && cp ../*.changes ."
      action :run
    end
  end
elsif platform_family?('rhel')
  if Dir.glob("#{node['dest_dir']}/passbolt-*.rpm").empty? then
    package 'RHEL: Install dev dependencies' do
      package_name ['rpmdevtools', 'rpmlint', 'rsync', 'selinux-policy-devel', 'rpm-build']
      action :install
    end

    execute 'RHEL: Setup RPM devtree' do
      cwd     "#{node['dest_dir']}"
      command "rpmdev-setuptree"
      action :run
    end  

    execute 'RHEL: Build Passbolt RPM package' do
      cwd     "#{node['dest_dir']}"
      command "PASSBOLT_FLAVOUR=#{node['passbolt_flavour']} \
                PKG_VERSION=#{node['passbolt_version']} \
                /bin/sh rpm/scripts/build-passbolt-server.sh"
      action :run
    end

    execute 'RHEL: Build passbolt-selinux RPM package' do
      cwd     "#{node['dest_dir']}"
      command "PKG_VERSION=0.1 /bin/sh rpm/scripts/build-passbolt-selinux.sh"
      action :run
    end

    execute 'RHEL: Copy packages in repository' do
      cwd     "#{node['dest_dir']}"
      command "cp -rf ~/rpmbuild/RPMS/noarch/passbolt-* ."
      action :run
    end
  end
end
