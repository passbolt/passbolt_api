title 'Passbolt package break and recover healthcheck'

control 'passbolt-break-01' do
  impact 0.9
  title 'Check passbolt package recovery'
  desc 'Check that passbolt package has been recovered and installed'

  describe package("passbolt-#{input('passbolt_flavour')}-server") do
    it { should be_installed }
  end
end
