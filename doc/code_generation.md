# Code Generation

SOme files of this project are generated using the ddd-gen code generator from https://github.com/flub78/ddd-gen

## Generation

In a windows console:

    cd build
    setenv.bat

    workflow boards -c api_controller -a compare

    workflow boards -c model_test -a install boards
    workflow boards -c model_test -a install columns
    workflow boards -c model_test -a install boards
