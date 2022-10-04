export type Result = {
    _id: string,
    _score: number,
    _source: {
        project_id: number,
        pair_id: number,
        type: string,
        key: string,
        value: string,
        translated: boolean,
        latest: boolean
    },
    highlight?: {
        value: string[]
    }
}

export type BackendResponse = {
    time: number,
    results: Result[]
}
