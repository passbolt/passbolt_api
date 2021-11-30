#
# Cookbook:: docker_test
# Recipe:: passbolt_install_and_purge
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

if platform_family?('debian')
  apt_update
end

include_recipe '::passbolt_install'

package "passbolt-#{node['passbolt_flavour']}-server" do
  action :purge
end
