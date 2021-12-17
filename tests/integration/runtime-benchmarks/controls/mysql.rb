title 'Check passbolt database'

sql_username = 'passboltadmin'
sql_user_password = 'hawhawhaw'

sql=mysql_session(sql_username, sql_user_password)

control 'passbolt-mysql-0' do
  impact 1
  title 'Check that passbolt database is present'
  desc 'Passbolt installation has created its database'

  describe sql.query('show databases;') do
    its('stdout') { should include('passboltdb') }
  end
end
