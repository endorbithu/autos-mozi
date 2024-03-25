import React, { useEffect, useState } from "react";

export default function Movies() {
    const [data, setData] = useState([]);

    const fetchData = () => {
        fetch(`/api/movie`)
            .then((response) => response.json())
            .then((actualData) => {
                console.log(actualData);
                setData(actualData.data);
                console.log(data);
            })
            .catch((err) => {
                console.log(err.message);
            });
    };

    useEffect(() => {
        fetchData();
    }, []);


    return <div className="container">
        <div className="row my-5">
            <div className="col-md-8 mx-auto">
                <h1>Movies</h1>
            </div>
            <div className="App">
                <tbody>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Desc</th>
                    <th>Lang</th>
                    <th>Age restrict</th>
                </tr>
                {data.map((item, index) => (
                    <tr key={index}>
                        <td>
                            <img src={item.cover_img} alt="" height={100} />
                        </td>
                        <td>{item.title}</td>
                        <td>{item.desc}</td>
                        <td>{item.lang}</td>
                        <td>{item.age_restrict}</td>
                    </tr>
                ))}
                </tbody>
            </div>
        </div>
    </div>;
}
