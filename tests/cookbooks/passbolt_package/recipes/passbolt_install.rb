#
# Cookbook:: docker_test
# Recipe:: passbolt_install
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

if platform_family?('debian')
  apt_update

  # TODO: Change this command when the repo is available

  execute "Install passbolt" do
    command  "DEBIAN_FRONTEND=noninteractive apt-get install -y openssl /tmp/passbolt/passbolt*.deb \
              && service php$(php -r 'echo PHP_VERSION;' | sed 's:\\(7\\.[2-4]\\).*:\\1:')-fpm start #{node.has_key?(:parameters) ? '' : '&& service nginx start'}"
    action   :run
  end
elsif platform_family?('rhel')
  package 'RHEL: Install dependencies' do
    package_name ['rpmdevtools', 'bc', 'createrepo', 'firewalld']
    action :install
  end

  execute "Setup remirepo" do
    cwd     "#{node['dest_dir']}"
    command  "/bin/sh rpm/scripts/setup-remirepo.sh"
    action   :run
  end

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

  service 'firewalld' do
    action :restart
  end

  execute "Configure passbolt" do
    command "/usr/local/bin/passbolt-configure \
              -P passbolt \
              -p hawhawhaw \
              -d passboltdb \
              -u passboltadmin \
              -H 127.0.0.1 \
              -e -n"
    action :run
  end
end
