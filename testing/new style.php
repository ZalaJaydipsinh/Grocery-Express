<style>
    .center {
        width: 120px;
        margin: 0 auto;
    }

    .category {
        max-height: 360px;
        overflow-x: auto;
        display: flex;
        position: relative;
    }

    .category::-webkit-scrollbar {
        display: none;
    }

    .card {
        margin-right: 10px;
        min-width: 150px;
        width: 150px;
        height: 300px;
        /* overflow-y: hidden; */
    }

    .main {
        height: 340px;
        margin-top: 20px;
        margin-left: 5px;
    }

    .main-heading {
        margin-left: 2px;
        /* border-left: 5px solid tomato; */
        float: left;
        margin-top: 2px;
    }



    /* button  */
    input[type="number"] {
        -webkit-appearance: textfield;
        -moz-appearance: textfield;
        appearance: textfield;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }

    .number-input {
        border: 1px solid grey;
        display: inline-flex;
    }

    .number-input,
    .number-input * {
        box-sizing: border-box;
    }

    .number-input button {
        outline: none;
        -webkit-appearance: none;
        background-color: transparent;
        border: none;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        cursor: pointer;
        margin: 0;
        position: relative;
    }

    .number-input button:before,
    .number-input button:after {
        display: inline-block;
        position: absolute;
        content: '';
        width: .5rem;
        height: 2px;
        background-color: #212121;
        transform: translate(-50%, -50%);
    }

    .number-input button.plus:after {
        transform: translate(-50%, -50%) rotate(90deg);
    }

    .number-input input[type=number] {
        font-family: sans-serif;
        max-width: 3rem;
        padding: .5rem;
        border: 1px solid grey;
        border-width: 0 2px;
        font-size: 1rem;
        height: 2rem;
        font-weight: bold;
        text-align: center;
    }

    .left,
    .right {
        position: relative;
        border-radius: 50%;
        font-weight: bold;
        z-index: 99;
        margin-top: -200px;
        opacity: 0.6;
        transition: opacity .5s;
        color: darkturquoise;
    }

    .right {
        float: right;
    }

    .left {
        float: left;
    }

    .left:hover,
    .right:hover {
        opacity: 1;
    }

    @media (max-width: 500px) {

        .left,
        .right {
            display: none;
        }
    }

    .detail {
        min-height: 95px;
    }

    .cart {
        position: absolute;
        width: 130px;
        left: 10px;
        bottom: 5px;
    }
</style>