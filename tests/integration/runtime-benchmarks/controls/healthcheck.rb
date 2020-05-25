title 'Passbolt runtime healthcheck'

control 'passbolt-runtime-01' do
  impact 0.9
  title 'Check if passbolt responds to the install url'
  desc 'Check that passbolt is running and waiting to be configured'
  describe http('http://127.0.0.1/install') do
    its('status') { should eq 200 }
    its('body') { should include 'Passbolt is not configured yet!' }
  end
end

control 'passbolt-runtime-02' do
  impact 1
  title 'Check if passbolt replies'
  desc 'Check if passbolt app is responding'
  describe http('http://127.0.0.1/install/system_check') do
    its('status') { should eq 200 }
    its('body') { should include 'Open source password manager for teams' }
  end
end
