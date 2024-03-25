import React, { useEffect, useState } from "react";

export default function Screenings() {
    const [data, setData] = useState([]);

    const fetchData = () => {
        fetch(`/api/screening`)
            .then((response) => response.json())
            .then((actualData) => {
                console.log(actualData);
                setData(actualData.data);
                console.log(data);
            })
            .catch((err) => {
                console.log(err);
            });
    };

    useEffect(() => {
        fetchData();
    }, []);


    return <div className="container">
        <div className="row my-5">
            <div className="col-md-8 mx-auto">
                <h1>Screenings</h1>
            </div>
            <div className="App">
                <tbody>
                <tr>
                    <th>Datetime</th>
                    <th>Title</th>
                    <th>Available seat</th>
                </tr>
                {data.map((item, index) => (
                    <tr key={index}>
                        <td>{item.datetime}</td>
                        <td>{item.movie.title}</td>
                        <td>{item.available_seats}</td>
                    </tr>
                ))}
                </tbody>
            </div>
        </div>
    </div>;
}
