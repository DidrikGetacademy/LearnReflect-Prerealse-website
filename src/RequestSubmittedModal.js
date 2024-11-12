import React from "react";
import './Css/modal.css'

const Modal = ({ message, onClose }) => {
    return(
        <div className="modal-overlay">
            <div className="modal-content">
                <h2>{message}</h2>
                <button className="ModalButton" onClick={onClose}>Close</button>
            </div>
        </div>
    );
};

export default Modal;