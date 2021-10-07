load("ext://docker_build_sub", "docker_build_sub")
# Allow certain kubernetes contexts to deploy to and avoid the "accidental prod deploy"
allow_k8s_contexts('kind-kind')

docker_build_sub('passbolt-ce', '../passbolt_docker', child_context='.',
    extra_cmds=['COPY . /var/www/passbolt',
                'RUN apt-get update && apt-get install -y git unzip \
                      && EXPECTED_SIGNATURE=$(curl -s https://composer.github.io/installer.sig) \
                      && curl -o composer-setup.php https://getcomposer.org/installer \
                      && php composer-setup.php --1 \
                      && mv composer.phar /usr/local/bin/composer \
                      && composer install -n \
                      && chown -R www-data:www-data vendor'
                ],
    live_update=[
        sync('.', '/var/www/passbolt'),
        run('cd /var/www/passbolt && composer install -n && chown -R www-data:www-data vendor', trigger=['./composer.json'])
        ])
# Helm chart path
path = '../../charts/charts-passbolt'
watch_file(path)
watch_file('../passbolt_docker')
yaml = helm(
  path,
  name = 'passbolt-ce',
  namespace = 'on-prem',
  values = ["{}/values-local-ce.yaml".format(path)],
  )
k8s_yaml(yaml)

k8s_resource('passbolt-ce-job-enable-selenium', resource_deps=['passbolt-ce-depl-srv'])
k8s_resource('passbolt-ce-depl-srv', resource_deps=['passbolt-ce-job-init-databases'])
k8s_resource('passbolt-ce-job-init-databases', resource_deps=['mariadb']) 
