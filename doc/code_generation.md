# Code Generation

SOme files of this project are generated using the ddd-gen code generator from https://github.com/flub78/ddd-gen

## Generation

In a windows console:

    cd build
    setenv.bat

    workflow -a compare -c api_controller boards
    workflow -a compare -c api_model boards
    workflow -a compare -c factory boards
    workflow -a compare -c test_model boards
    workflow -a compare -c test_api boards

