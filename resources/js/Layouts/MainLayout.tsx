import type { PropsWithChildren } from "react";
import { Head } from "@inertiajs/react";

type Props = PropsWithChildren<{
	title?: string;
}>;

export function MainLayout({ title, children }: Props) {
	return (
		<>
			<Head title={title} />
			<div className="min-h-screen bg-gray-50">
				<main>{children}</main>
			</div>
		</>
	);
}
