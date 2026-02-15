import { MainLayout } from "@/Layouts/MainLayout";

export default function Welcome() {
	return (
		<MainLayout title="Welcome">
			<div className="flex min-h-screen items-center justify-center">
				<div className="text-center">
					<h1 className="text-4xl font-bold text-gray-900">
						Laravel + React + Filament
					</h1>
					<p className="mt-4 text-lg text-gray-600">
						Template is ready to use
					</p>
				</div>
			</div>
		</MainLayout>
	);
}
