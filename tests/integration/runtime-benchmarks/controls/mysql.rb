title 'Check passbolt database'

sql=mysql_session('root','')

control 'passbolt-mysql-0' do
  impact 1
  title 'Check that passbolt database is present'
  desc 'Passbolt installation has created its database'

  describe sql.query('show databases;') do
    its('stdout') { should include('passboltdb') }
  end
end
