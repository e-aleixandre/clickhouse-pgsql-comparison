import {BackendResponse} from "../types/types";
import {useState} from "react";

export const useGetAllContents: () => Promise<BackendResponse> = async () => {
    const response = await fetch('http://127.0.0.1:8080/Client.php');
    const jsonData = await response.json();

    return jsonData as BackendResponse;
};

export const useMatchContents: (query: string, type: string) => Promise<BackendResponse> = async (query, type) => {
    const response = await fetch(`http://127.0.0.1:8080/Client.php?method=search&search=${query}&type=${type}`);
    const jsonData = await response.json();

    return jsonData as BackendResponse;
}

export const useUpdateContent: (id: number, value: string) => Promise<BackendResponse> = async (id, value) => {
    const response = await fetch(`http://127.0.0.1:8080/Client.php?method=update&id=${id}&value=${value}`);
    const jsonData = await response.json();

    return jsonData as BackendResponse;
}

export const useGetTranslatedPercentage: (type: string) => number = type => {
    const [percentage, setPercentage] = useState(0);

    return percentage;
}
