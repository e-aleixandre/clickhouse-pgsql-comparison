import {useEffect, useState} from 'react'
import './App.css'
import {Search} from "./components/Search/Search";
import {ResultsTable} from "./components/ResultsTable/ResultsTable";
import {useGetAllContents, useMatchContents, useUpdateContent} from "./helper/Client";
import {Result} from "./types/types";
import {Filter} from "./components/Filter/Filter";
import {PercentageTable} from "./components/PercentageTable/PercentageTable";

function App() {
  const [results, setResults] = useState<Result[]>([]);
  const [time, setTime] = useState<number>(0);
  const [query, setQuery] = useState<string>("");
  const [type, setType] = useState<string>("all");

  const getContents: () => void = async () => {
      const backendResponse = await useGetAllContents();

      setResults(backendResponse.results);
      setTime(backendResponse.time);
  }

  const searchContents: () => void = async () => {
      if ('' === query) {
          getContents();
          return;
      }

      const backendResponse = await useMatchContents(query, type);

      setResults(backendResponse.results);
      setTime(backendResponse.time);
  }

  const updateContent: (number, string) => void = async (id, value) => {
      const backendResponse = await useUpdateContent(id, value);

      const newResults = results.map(result => {
          if (result._id === id) {
              return backendResponse.results[0];
          }

          return result;
      });

      setResults(newResults);
      setTime(backendResponse.time);
  }

  useEffect(() => {
      getContents();
  }, []);

  useEffect(() => {
      searchContents()
  }, [query, type])

  return (
    <div className="App">
      <Search query={query} setQuery={setQuery} searchFunction={searchContents} />
        <Filter setType={setType}/>
        <small className="mt-4" style={{display: "block"}}>Last query took {time * 1000} ms</small>
      <ResultsTable results={results} updateFunction={updateContent}/>
        <PercentageTable/>
    </div>
  )
}

export default App
