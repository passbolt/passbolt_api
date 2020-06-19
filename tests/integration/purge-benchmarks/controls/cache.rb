title 'Check passbolt has cleared debconf cache'

control 'passbolt-cache-0' do
  impact 0.5
  title 'debconf cache data'
  desc 'debconf cached parameters for passbolt should not exist'
  describe command("debconf-get-selections | grep passbolt") do
    its('stdout') { should eq '' }
  end
end
