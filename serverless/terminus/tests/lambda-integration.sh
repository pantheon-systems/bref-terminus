#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

SELF_DIRNAME="$(dirname -- "$0")"
source "$SELF_DIRNAME/../../../tests/test_utils.sh"

cd "${SELF_DIRNAME}/.."

if [[ ! -f "./.env" ]]; then
	echo "⚠️ Cannot deploy test lambdas without Terminus .env"
	exit 1
fi

start "deploying λ"
stage="test$(date +%s)"
serverless deploy --region us-west-2 --stage "${stage}"

TESTS_FAILED=0
echo
if bash tests/functional.sh "${stage}" ; then
	success "λ tests on stage '${stage}'"
else
	echo "❌ λ tests failed"
	TESTS_FAILED=1
fi

start "cleaning up λ"
serverless remove --region us-west-2 --stage "${stage}"

exit ${TESTS_FAILED}
