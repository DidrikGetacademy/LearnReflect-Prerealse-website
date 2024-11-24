import React, { useEffect, useState } from "react";
import Upscaled1 from './Images/Oppskalert1.png';
import Upscaled2 from './Images/Oppskalert2.png';
import right from './Images/right.jpg';
import left from './Images/left.jpg';
import software from './Images/software.png'
import './Css/imageCarousal.css';
const images = [software, Upscaled1, Upscaled2];

function ImageCarousel() {
    const [currentindex, setCurrentIndex] = useState(0);// statevariabel som innholder nåværende bilde som vises.
    const totalImages = images.length; // variabel som holder lengden på arrayet med alle bildene.


    // custom funksjon for å sette forrige bilde.
    const prevImage = () => {
        setCurrentIndex((previndex) => (previndex - 1 + totalImages) % totalImages); // setter bilde index til forrige bildet i arrayet..
    }

    const nextImage = () => {
        setCurrentIndex((nextimage) => (nextimage + 1) % totalImages); // setter currentindex til neste i arrayet..
    }



    useEffect(() => {

    }, [])

    return (
        <div>
            <div className="Image-Scroll">
                <div className="image-container">
                    <img className="SoftWareImg" src={images[currentindex]} alt="SoftwareIMG" />
                </div>
                <div className="navigation-buttons">
                    <button className="nav-button" src={left} onClick={prevImage}>←</button> 
                    <button className="nav-button" src={right}  onClick={nextImage}>→</button> 
                </div>
            </div>
        </div>

    );
}

export default ImageCarousel;