import React, { useState, useRef } from "react";
import { PayPalButtons, usePayPalScriptReducer } from "@paypal/react-paypal-js"; //PayPal script is loading and can be used to change the values of the options of the PayPal script and at the same time reload the script with the updated parameters.
import upscaled from './Images/bedre.jpg'
import './Css/checkout.css';
import ImageCarousel from "./ImageCarousel";
import Modal from "./RequestSubmittedModal";
import { useNavigate } from "react-router-dom";
function Checkout() {
    const Navigate = useNavigate();
    const [{ options, isPending }, dispatch] = usePayPalScriptReducer();// paypal script reducer manage a way to manage the state of the paypalscript, sutchs as client side with buttons, if a customer choose another currency, reducer will update this.
    const [currency, setCurrency] = useState(options.currency);
    const emailref = useRef("");
    const PURCHASE_AMOUNT = "20.00";



    const onCurrencyChange = ({ target: { value } }) => {
        setCurrency(value);
        dispatch({ type: 'resetOptions', value: { ...options, currency: value, }, });
    }


    const onCreateOrder = (data, actions) => {
        console.log("Data in the oncreateOrder Function: ", data)
        return actions.order.create({
            purchase_units: [{ amount: { value: PURCHASE_AMOUNT, }, },],
        });
    }


    const ClosePaymentModal = () => {
        Navigate('/');
    }


    const onApprove = (data, actions) => {
        const emailInput = emailref.current.value;

        if (!emailInput) {
            alert('Please provide your email!');
            return;
        }
        
        return actions.order.capture().then((details) => {
            const orderAmount = details.purchase_units[0].amount.value;
            const OrderId = details.id;
            const name = details.payer.name.given_name;
            const email = emailInput || details.payer.email_address;


            if (details.id === '') {
                console.log('Order_id is empty');
                alert('Error, Details ID is empty');
                return;
            }

            if (email === '') {
                console.log('Email is empty');
                alert("Please write in your email");
                return;
            }

            console.log('Details from paypal transaction: ', details)
            if (orderAmount !== PURCHASE_AMOUNT) {
                console.log("Payment amount does not match", orderAmount, PURCHASE_AMOUNT);
                return;
            }

            fetch("https://learnreflects.com/Server/Generate_PrivateKey.php", {
                method: "POST",
                body: JSON.stringify({ email: email, amount: "20.00", order_id: OrderId }),
                headers: { "Content-Type": "application/json" },
            })
                .then((response) => response.text())
                .then((text) => {
                    console.log("Raw response text", text);
                    try {
                        const data = JSON.parse(text);
                        console.log("Parsed JSON: ", data);
                        if (data.key_code) {
                            console.log("Server keycode: ", name + " " + data.key_code);
                            return <Modal details={details} onClose={ClosePaymentModal} message="Thanks for payment! you will receive details and download link on your email" />
                        } else if (data.error) {
                            console.log("Server error: ", data.error);
                        } else if (data.message) {
                            console.log("Server message: ", data.message)
                        }
                    } catch (error) {
                        console.error("Error parsing JSON: ", error, text);
                    }
                }).catch((error) => {
                    console.error("Error during fetch: ", error)
                })
        });
    };



    return (
        <div style={{ display: "flex", alignItems: "center", height: "100vh", width: "100vw", justifyContent: "center" }}>
            <img className="stuker" alt="background" src={upscaled} />
            <div className="checkout">
                <ImageCarousel />
                <div className="payment-section">
                    <label>Please choose a payment method below</label>
                    {isPending ? (
                        <p>Loading...</p>
                    ) : (
                        <>
                            <select className="currency-select" value={currency} onChange={onCurrencyChange}>
                                <option className="option" value="USD">💵 USD</option>
                                <option className="option" value="EUR">💶 Euro</option>
                            </select>
                        </>
                    )}
                    <label>Please write in your email for details:</label>
                    <input type="email" placeholder="E-Mail" className="EmailInput" ref={emailref} />
                    <PayPalButtons
                        style={{ layout: "vertical" }}
                        createOrder={(data, actions) => onCreateOrder(data, actions)}
                        onApprove={(data, actions) => onApprove(data, actions)}
                        onError={(err) => console.error("Paypal error: ", err)}
                        disabled={!emailref.current.valueOf}
                    />
                </div>
            </div>
        </div>
    );


}

export default Checkout;

