import axios from "axios";
import React, { useEffect, useState } from "react";
function EarlyAccess() {
  const [count, setCount] = useState(0);

  useEffect(() => {
    axios
      .get("https://learnreflects.com/Server/get_user_count.php")
      .then(response => setCount(response.data.count))
      .catch(err => console.log(err));
  }, []);

  return (
    <div className="SignedUp-Container">
        <span>
          join {count}+ others in getting early access!
        </span>
    </div>
  );
}

export default EarlyAccess;
