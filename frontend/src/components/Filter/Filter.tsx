export const Filter = (props) => {
    const types = [
        "all",
        "theme",
        "post",
        "article",
        "page",
        "metafield",
        "product"
    ];

    const handleChange = (value) => {
        props.setType(value);
    }

    return (
        <select onChange={(e) => handleChange(e.target.value)} name="type">
            {types.map(type => <option key={type} value={type}>{type}</option>)}
        </select>
    )
}