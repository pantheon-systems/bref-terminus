# Terminus Yggdrasil Service

This collection of lambda functions use the Terminus API in order to provide a Yggdrasil client library.

## Setup

Before running any code locally, you will need to setup your .env file. Use the [dot-env.dist](./dot-env.dist) file to create your initial .env file. To use Terminus lambdas with your sandbox, you will need to set `TERMINUS_HOST` (the hermes address for your onebox) and, because we cannot use machine tokens with a sandbox, `TERMINUS_SESSION_JWT`.

Follow the instructions for "Using Terminus with your Sandbox" on the [Sandbox Badge][1], and copy the session JSON string from `~/.terminus-sandbox/cache/session` to `TERMINUS_SESSION_JWT` in `./.env`.

The secrets in your .env file will be used for the functional and integration tests, and for running code with `serverless invoke local`, as shown below. Deployed code will use the secrets stored in the AWS Secret Manager. Authorization to fetch these secrets is granted by the iamRoleStatements section of the [serverless.yml](./serverless.yml) file.

### Session Token in AWS Secret Manager

```bash
# Create the Initial Secret
$ aws secretsmanager create-secret --name ${STAGE}/autopilot/terminus_session_jwt --secret-string '{"session":"...","expires_at":123,"user_id":"..."}'
# Update an existing secret
$ aws secretsmanager update-secret --secret-id pwt/autopilot/terminus_session_jwt --secret-string '{"session":"...","expires_at":123,"user_id":"..."}'
```

TODO: For production `TERMINUS_SESSION_JWT`, rotate with cron Circle CI workflow.

TODO: Stop using Terminus

[1]: https://getpantheon.atlassian.net/wiki/spaces/VULCAN/pages/144801793/Pantheon+Sandbox+Environments+formerly+Onebox#PantheonSandboxEnvironments(formerlyOnebox)-UsingTerminuswithyourSandbox

### Circle CI Terminus Integration Tests

The Circle CI tests test against fixture sites on Production, using a machine token to generate the session. The TERMINUS_TOKEN environment variable in Circle CI should be added to the organization [CI Fixtures for Projects][2] to authenticate access to the following sites:

- [ci-drops-8-autopilot](https://admin.dashboard.pantheon.io/sites/7ad15379-cad6-43ff-850e-0c8ef97a83a9)
- [CI WordPress Autopilot](https://admin.dashboard.pantheon.io/sites/cafd7a3a-2358-471c-abe6-258033e22617)
- [CI Empty Autopilot](https://admin.dashboard.pantheon.io/sites/b1bbec4f-1377-44d6-8fc9-a289e6447fa1)

[2]: https://admin.dashboard.pantheon.io/organizations/5ae1fa30-8cc4-4894-8ca9-d50628dcba17

### Setting "TERMINUS_HOST" in the Lambda Runtime

#### Serverless.yml

The Serverless.yml file includes custom variables for setting the TERMINUS_HOST environmental variable when deploying the lambda functions. Committing an option for every sandbox is obviously untenable, but a limited list may be fine until we're no longer using Terminus Lambdas.

#### .env File

When deploying for a sandbox not listed in Serverless's `custom.TERMINUS_HOST`, set `TERMINUS_HOST` but not `TERMINUS_SESSION_JWT` in `./.env` before running `serverless deploy`. Your sandbox will then use Secrets Manager for the `TERMINUS_SESSION_JWT`, but with no Lambda environment variable set for `TERMINUS_HOST`, fall back to the .env file bundled with the Lambda. No `TERMINUS_HOST` means the Lambda will run against production (as in the Serverless/Lambda tests)

## Example

To get info on a site:

```bash
$ serverless invoke local -f siteinfo -d '{"site":"ci-drops-8-autopilot"}'
```

To do the same thing from a dev deployment:

```bash
$ serverless deploy
$ serverless invoke -f siteinfo -d '{"site":"ci-drops-8-autopilot"}'
```

## Services

#### apply_upstream_updates

Applies upstream updates to the indicated development environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### clear_env_cache

Clears the environment's cache.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### clone_database

Clones the database from one environment to another environment.

_Inputs:_

- site: Site name or site ID
- source: Environment name which provides the database/files
- target: Environment name which receives the database/files

#### clone_files

Clones files from one environment to another environment.

_Inputs:_

- site: Site name or site ID
- source: Environment name which provides the database/files
- target: Environment name which receives the database/files

#### connection_info

Returns connection information for Git, SFTP, MySQL, and Redis.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### create_backup

Creates a backup of the indicated environment.

_Inputs:_

- site: Site name or site ID
- env: Environment name

#### create_env

Creates a multidev environment from the indicated environment with the given name.

_Inputs:_

- site: Site name or site ID
- source: Environment name
- target: New environment name

#### delete_env

Deletes the indicated multidev environment.

_Inputs:_

- site: Site name or site ID
- env: Environment name

#### deploy_env

Deploys code to the test or live environment.

_Inputs:_

- site: Site name or site ID
- env: Environment name

#### disable_lock

Disables HTTP basic auth on a site environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### enable_lock

Enables HTTP basic auth on a site environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name
- username: Username for HTTP basic auth
- password: Password for HTTP basic auth

#### env_diffstat

Returns the diffstat for the site environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### env_info

Returns information on the given site environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### list_domains

Returns a list of domains for the given site environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### list_multidevs

List the multidev environments for a site.

_Inputs:_

- site: Site name or site ID

#### list_workflows

List workflows associated with a site.

_Inputs:_

- site: Site name or site ID

#### lock_info

Returns information about HTTP basic auth for a given site and environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### merge_to_dev

Merges code commits from a multidev environment into the dev environment.

_Inputs:_

- site: Site name or site ID
- env: Environment name

#### set_connection

Set's the site environment's connection mode.

_Inputs:_

- site: Site name or site ID
- env: Source environment name
- mode: Connection mode, must be either `git` or `sftp`

#### site_info

Returns a site's information.

_Inputs:_

- site: Site name or site ID

#### upstream_updates_status

Returns the upstream updates status of a site's environment.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### wake_env

Wake a site's environment by pinging it.

_Inputs:_

- site: Site name or site ID
- env: Source environment name

#### whoami

Returns the Pantheon username associated with the lambda credentials.

_Inputs:_

None.

## Testing

Before running the tests, first set up your .env file as described in [Setup](#Setup), above.

### Composer Tests

`composer test` will run all tests. Alternately, you may instead run individual tests as shown below:

| Test Type         | Test Command         |
| ----------------- | -------------------- |
| Lint              | composer lint        |
| Code style        | composer cs          |
| Fix stype errors  | composer cbf         |
| Unit tests        | composer unit        |
| Integration tests | composer integration |

### Serverless/Lambda Tests

Local Functional Tests run with `serverless invoke local`, which is extremely slow to invoke PHP Lambda runtime. Alternativly, `make integration-test-lambda` will deploy the Lambda to Serverless at a randomly-generated "stage", test there, and clean up when done.

Note, these tests will deploy the .env file if it exists locally, and use those secrets instead of AWS Secrets Manager. Like `composer integration`, The functional tests are intended to run against production fixtures.

| Test Type                | Test Command                 |
| ------------------------ | ---------------------------- |
| Functional tests         | make functional-test-local   |
| Lambda integration tests | make integration-test-lambda |
