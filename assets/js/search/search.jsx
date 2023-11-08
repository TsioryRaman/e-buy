import React, { useCallback, useRef, useState } from "react";
import { createRoot } from "react-dom/client";
import { debounce } from "../functions/timer.js";
import { createPortal } from "react-dom";
const TIMEOUT = 300;
const API_SEARCH = "/api/search";

export class SearchElement extends HTMLElement {
  connectedCallback() {
    let action = this.getAttribute("aria-action");
    const app = createRoot(this);
    app.render(<Search action={action} />);
  }
}

const Search = ({ action }) => {
  const query = useRef("");
  const [open, setOpen] = useState(false)

  const handleChange = useCallback(
    debounce(async (e) => {
      const data = await fetch(`${API_SEARCH}?q=${e.target.value}`);
      console.log(data);
    }, TIMEOUT),
    []
  );

  const clsForm = () => {
    !open ? "" : ""
  }

  return (
    <React.Fragment>
    <div>
      <form
        className="flex flex-row items-center justify-center md:visible cursor-pointer  duration-300 md:bg-gray-100 rounded-full overflow-hidden md:hover:text-white md:hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700"
        action={action}
      >
        <label
          className=" h-full bottom-0 md:flex items-center pl-4 hidden my-0 mx-0 pr-4"
          htmlFor="search"
        >
          <icon-feather name="search" size="24" />
        </label>
        <input
          type="text"
          ref={query}
          onChange={handleChange}
          placeholder="Rechercher un article..."
          onClick={() => setOpen(true)}
          className="text-sm hidden md:flex focus:w-96 duration:300 bg-transparent w-auto px-8 py-0 mx-0 shadow-none border-none hover:placeholder-gray-400 focus:placeholder-transparent placeholder-gray-700 dark:text-white text-gray-700"
          name="q"
          id="search"
        />
      </form>
    </div>
    </React.Fragment>
  );
};
