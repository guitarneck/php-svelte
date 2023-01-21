# Benchmark rollup/vite

Generated at 2023-01-21 19:23

## Usage:

```shell
$ /usr/bin/time --verbose --output=./benchmark-rollup.time.txt make build-rollup
$ /usr/bin/time --verbose --output=./benchmark-vite.time.txt make build-vite
$ php -f compare-time.php > benchmark.md
```

| rollup                                                     | vite                                                       |
|------------------------------------------------------------|------------------------------------------------------------|
| ***Command being timed: "make build-rollup"***             | ***Command being timed: "make build-vite"***               |
| ***User time (seconds): 3.93***                            | ***User time (seconds): 4.42***                            |
| ***System time (seconds): 0.92***                          | ***System time (seconds): 0.87***                          |
| ***Percent of CPU this job got: 50%***                     | ***Percent of CPU this job got: 54%***                     |
| ***Elapsed (wall clock) time (h:mm:ss or m:ss): 0:09.56*** | ***Elapsed (wall clock) time (h:mm:ss or m:ss): 0:09.81*** |
| Average shared text size (kbytes): 0                       | Average shared text size (kbytes): 0                       |
| Average unshared data size (kbytes): 0                     | Average unshared data size (kbytes): 0                     |
| Average stack size (kbytes): 0                             | Average stack size (kbytes): 0                             |
| Average total size (kbytes): 0                             | Average total size (kbytes): 0                             |
| ***Maximum resident set size (kbytes): 189424***           | ***Maximum resident set size (kbytes): 209012***           |
| Average resident set size (kbytes): 0                      | Average resident set size (kbytes): 0                      |
| Major (requiring I/O) page faults: 0                       | Major (requiring I/O) page faults: 0                       |
| ***Minor (reclaiming a frame) page faults: 57889***        | ***Minor (reclaiming a frame) page faults: 79810***        |
| ***Voluntary context switches: 70236***                    | ***Voluntary context switches: 67321***                    |
| ***Involuntary context switches: 523***                    | ***Involuntary context switches: 1024***                   |
| Swaps: 0                                                   | Swaps: 0                                                   |
| File system inputs: 0                                      | File system inputs: 0                                      |
| File system outputs: 8                                     | File system outputs: 8                                     |
| Socket messages sent: 0                                    | Socket messages sent: 0                                    |
| Socket messages received: 0                                | Socket messages received: 0                                |
| Signals delivered: 0                                       | Signals delivered: 0                                       |
| Page size (bytes): 4096                                    | Page size (bytes): 4096                                    |
| Exit status: 0                                             | Exit status: 0                                             |
