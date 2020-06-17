#
# Cookbook:: docker_test
# Recipe:: passbolt_package_build
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

if (Dir.glob("#{node['dest_dir']}/passbolt-*.deb").empty? or
    Dir.glob("#{node['dest_dir']}/vendor/*").empty?) then
  apt_update 'all platforms' do
    action :update
  end
end

if Dir.glob("#{node['dest_dir']}/vendor/*").empty? then
  package 'Install composer' do
    package_name 'composer'
    action :install
  end

  execute 'Download vendors' do
    cwd     "#{node['dest_dir']}"
    command 'composer install -o --prefer-dist --no-dev --ignore-platform-reqs --no-interaction'
    action :run
  end
end

if Dir.glob("#{node['dest_dir']}/passbolt-*.deb").empty? then
  package 'Install dev dependencies' do
    package_name ['devscripts', 'build-essential', 'apt-utils', 'fakeroot', 'equivs', 'cdbs']
    action :install
  end

  execute 'Build debian package' do
    cwd     "#{node['dest_dir']}"
    command "export PASSBOLT_FLAVOUR=#{node['passbolt_flavour']} \
             && make -f debian/rules debian/control \
             && mk-build-deps -irt'apt-get --no-install-recommends -yV' debian/control && dpkg-checkbuilddeps \
             && debuild --preserve-envvar PASSBOLT_FLAVOUR -us -uc -b -i -I  \
             && cp ../*.deb . \
             && cp ../*.build . \
             && cp ../*.buildinfo . \
             && cp ../*.changes ."
    action :run
  end
end
