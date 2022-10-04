import {useEffect, useState} from "react";
import {useGetTranslatedPercentage} from "../../helper/Client";

export const PercentageTable = () => {
    const [tableRows, setTableRows] = useState([]);

    const style = {
        width: '100%',
        border: '1px solid white'
    }

    const types = [
        "theme",
        "post",
        "article",
        "page",
        "metafield",
        "product"
    ];

    useEffect(() => {
        const res = useGetTranslatedPercentage("product");

        /*const rows = types.map(type => {
            const response = useGetTranslatedPercentage(type);

            return (
                <tr>
                    <td>{type}</td>
                    <td>{response.percentage}%</td>
                </tr>
            )
        });*/

        setTableRows([]);
    }, []);

    return (
        <table style={style} className="mt-4">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Translated %</th>
                </tr>
            </thead>
            <tbody>
            {tableRows.map(row => row)}
            </tbody>
        </table>
    )
}