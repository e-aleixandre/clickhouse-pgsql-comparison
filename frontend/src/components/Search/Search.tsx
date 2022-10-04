export const Search = (props) => {
    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        props.setQuery(e.target.value);
    }

    return (
      <input
          type="text"
          placeholder="Search..."
          value={props.query}
          onChange={handleChange}
      />
    );
};