#
# Cookbook:: docker_test
# Recipe:: passbolt_install_breaks
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

if platform_family?('debian')
  apt_update

  execute "Install passbolt, expect to break, display output" do
    command "VERBOSE=1 DEBIAN_FRONTEND=noninteractive apt-get install -y #{node['parameters']} /tmp/passbolt/passbolt*.deb"
    ignore_failure true
    live_stream true
    action :run
  end
elsif platform_family?('rhel')
  execute "Setup local repository" do
    cwd     "#{node['dest_dir']}"
    command "/bin/sh rpm/scripts/setup-local-repository.sh"
    action :run
  end

  package "Install Passbolt" do
    flush_cache [ :before ]
    package_name "passbolt-#{node['passbolt_flavour']}-server"
    action :install
  end

  execute "Configure passbolt, expect to break, display output" do
    command "/usr/local/bin/passbolt-configure \
              -P passbolt \
              -p passbolt \
              -d passbolt \
              -u passbolt \
              -H pro.rockylinux8.jc \
              -e -n"
    ignore_failure true
    live_stream true
    action :run
  end
end