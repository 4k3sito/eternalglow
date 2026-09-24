declare global {
    interface Window {
        showModal: (id: string) => void;
        JSS_APP: {
            ajaxURL: string;
            templateURL: string;
			token: string;
			is_mobile: boolean;
			jss_token: string;
        };
    }
}
export {};