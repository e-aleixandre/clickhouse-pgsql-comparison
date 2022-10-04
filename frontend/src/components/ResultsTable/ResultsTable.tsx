import {Result} from "../../types/types";

export const ResultsTable = ({results, updateFunction}: {results: Result[], updateFunction}) => {
    if (results.length === 0) {
        return (
            <h4>No results... Change your query</h4>
        )
    }

    const style = {
        width: '100%',
        border: '1px solid white'
    }

    const resultRow: (Result) => JSX.Element = (result) => {
        const valueHtml = {
            __html: result.highlight ? result.highlight.value[0] :
                result._source.value
        }

        return (
          <tr className='result' key={result._id}>
              <td>
                  {result._id}
              </td>
              <td>
                  {result._source.type}
              </td>
              <td>
                  {result._source.key}
              </td>
              <td onBlur={(e) => updateFunction(result._id, e.target.innerText)} contentEditable="true" className='result-value' dangerouslySetInnerHTML={valueHtml}>
              </td>
              <td>
                  {result._source.translated ? 'True' : 'False'}
              </td>
          </tr>
        );
    };

    return (
        <table className="mt-4" style={style}>
            <thead>
                <tr style={{
                    borderBottom: '1px solid white'
                }}>
                    <th>Id</th>
                    <th>Type</th>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Translated</th>
                </tr>
            </thead>
            <tbody>
            {
                results.map(result => resultRow(result))
            }
            </tbody>
        </table>
    );
}