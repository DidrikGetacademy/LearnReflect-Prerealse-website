import React, { useEffect, useState } from "react";
import Upscaled1 from '../Images/Oppskalert1.png';
import Upscaled2 from '../Images/Oppskalert2.png';
import SoftwareIMG1 from '../Images/APP1.png'
import SoftwareIMG2 from '../Images/APP2.png'
import SoftwareIMG3 from '../Images/APP3.png'
import SoftwareIMG4 from '../Images/APP4.png'
import SoftwareIMG5 from '../Images/app5.png'
import SoftwareIMG6 from '../Images/app6.png'
import SoftwareIMG7 from '../Images/app7.png'
import SoftwareIMG8 from '../Images/app8.png'
import SoftwareIMG9 from '../Images/app9.png'
import SoftwareIMG10 from '../Images/app10.png'
import './Css/imageCarousal.css';
const images = [SoftwareIMG1, Upscaled1, Upscaled2,SoftwareIMG2,SoftwareIMG3,SoftwareIMG4,SoftwareIMG5,SoftwareIMG6,SoftwareIMG7,SoftwareIMG8,SoftwareIMG9,SoftwareIMG10];

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
                    <button className="nav-button"  onClick={prevImage}>←</button> 
                    <button className="nav-button"   onClick={nextImage}>→</button> 
                </div>
            </div>
        </div>

    );
}

export default ImageCarousel;