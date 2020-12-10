#!/bin/bash
# We do not want to `set -euo pipefail` because we want to pass $? to `assert`
set -u
IFS=$'\n\t'

# Include test utilities for assert_equals et. al.
SELF_DIRNAME="`dirname -- "$0"`"
source "$SELF_DIRNAME/../../../tests/test_utils.sh"

cd "$SELF_DIRNAME/.."

start "serverless/terminus functional tests"

STAGE_FLAG=''
LOCAL=''

stageParam="${1:-}"
if [[ -z "${stageParam}" || "${stageParam}" == 'local' ]]; then
	echo "Invoking Tests Locally"
	LOCAL='local'
else
	STAGE_FLAG="--stage ${stageParam}"
fi


actual=$(
	serverless invoke ${LOCAL} \
	-f whoami ${STAGE_FLAG}
)
assert_regex '"id":\s?"........-....-....-....-............"' "$actual" "checked who I am, and I am someone."


actual=$(
	serverless invoke ${LOCAL} \
	-f apply_upstream_updates ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","env":"dev"}'
)
assert_regex '........-....-....-....-............' "$actual" "applied upstream updates to dev environment on fixture site"

actual=$(
	serverless invoke ${LOCAL} \
	-f connection_info ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","env":"dev"}'
)
assert_regex '"sftp_host":\s?"appserver\.dev\.........-....-....-....-............\.drush\.in"' "${actual}" "connectioninfo returned information about fixture site"

actual=$(
	serverless invoke ${LOCAL} \
	-f create_backup ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","env":"dev"}'
)
assert_regex '........-....-....-....-............' "$actual" "created a backup of the dev environment on fixture site"

if [[ "${LOCAL}" != "local" ]]; then
	# If running locally we won't have valid JSON here, and will skip the
	# relevant test below.
	WORKFLOW_TO_CHECK_STATUS="${actual}"
fi

actual=$(
	serverless invoke ${LOCAL} \
	-f create_env ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","source":"dev","target":"func-test"}'
)
assert_regex '........-....-....-....-............' "$actual" "created the func-test environment on fixture site"

actual=$(
	serverless invoke ${LOCAL} \
	-f clone_database ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","source":"dev","target":"func-test"}'
)
assert_regex '........-....-....-....-............' "$actual" "cloned the database from dev to func-test on a fixture site"

actual=$(
	serverless invoke ${LOCAL} \
	-f clone_files ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","source":"dev","target":"func-test"}'
)
assert_regex '........-....-....-....-............' "$actual" "cloned the files from dev to func-test on a fixture site"


actual=$(
	serverless invoke ${LOCAL} \
	-f delete_env ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","env":"func-test"}'
)
assert_regex '........-....-....-....-............' "$actual" "deleted the func-test environment on a fixture site"

actual=$(
	serverless invoke ${LOCAL} \
	-f deploy_env ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","env":"dev","annotation":"Deployment from Autopilot"}'
)
assert_regex '........-....-....-....-............' "$actual" "deploys the dev environment to test on a fixture site"

actual=$(
	serverless invoke ${LOCAL} \
	-f merge_to_dev ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","env":"func-test"}'
)
assert_regex '........-....-....-....-............' "$actual" "merges the func-test environment to dev on a fixture site"

actual=$(
	serverless invoke ${LOCAL} \
	-f site_info ${STAGE_FLAG}\
	-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e"}'
)
assert_regex '"id":\s?"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e"' "${actual}" "siteinfo returned information about fixture site"

TEST_MESSAGE="workflow info returned information about fixture site workflow"
if [[ "${LOCAL}" == "local" ]]; then
	skip "${TEST_MESSAGE}"
else
	actual=$(
		serverless invoke ${LOCAL} \
		-f workflow_info_status ${STAGE_FLAG}\
		-d '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e","workflow":'"${WORKFLOW_TO_CHECK_STATUS}"'}'
	)
	assert_regex '........-....-....-....-............' "$actual" "${TEST_MESSAGE}"
fi

actual=$(
	serverless invoke ${LOCAL} \
	-f upstream_updates_status ${STAGE_FLAG}\
	--data '{"site":"aa3fd745-bbff-461a-a6d3-8dbb7d20b42e", "env":"upd-fixture"}'
)
assert_regex 'outdated' "$actual" "New upstream commits found on fixture site"


finish
