# bref-terminus

[![Deprecated](https://img.shields.io/badge/Pantheon-Deprecated-yellow?logo=pantheon&color=FFDC28)](https://pantheon.io/docs/oss-support-levels#deprecated)

This is a PoC that demonstrates using Terminus as a Yggdrasil client library
from an AWS lamda function.

### Setup

Copy `env.php.dist` to `env.php` and add a machine token.

### Example

To run the demo:

```
serverless invoke local -f function --data '{"site": "732afb71-43d4-40b4-8b63-bfab34652d6c"}'
```
