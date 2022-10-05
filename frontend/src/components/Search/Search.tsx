type SearchProps = {
    setQuery: (arg0: string) => void,
    query: string
}

export const Search = (props: SearchProps) => {
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